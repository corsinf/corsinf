# ================== CONFIGURACION ==================

# $txtFile   = "C:\xampp\htdocs\corsinf\Cron\dispositivos_activos.txt"
$txtFile   = "C:\Apache24\htdocs\php81\corsinf\Cron\dispositivos_activos_manual.txt"
# $logFolder = "C:\xampp\htdocs\corsinf\logs\talento_humano\dispositivos"
$logFolder = "C:\Apache24\htdocs\php81\corsinf\logs\talento_humano\dispositivos"

$runningJobs  = @{}
$finishedJobs = @{}
$blockedJobs  = @{}

$MAX_RETRIES     = 3
$SAFE_START_TIME = 15

if (-not (Test-Path $logFolder)) {
    New-Item -ItemType Directory -Path $logFolder | Out-Null
}

Write-Host "--- Iniciando Monitor de Dispositivos Corsinf ---" -ForegroundColor Cyan

# ================== LOOP PRINCIPAL ==================

while ($true) {

    $currentCommands = Get-Content $txtFile | Where-Object { -not [string]::IsNullOrWhiteSpace($_) }

    foreach ($cmdLine in $currentCommands) {

        $jobKey = $cmdLine.Trim()
        $safeName  = ($jobKey -replace '[^a-zA-Z0-9]', '_')
        $blockFile = Join-Path $logFolder "$safeName.blocked"

        # Bloqueado definitivo
        if (Test-Path $blockFile) {
            $blockedJobs[$jobKey] = $true
            continue
        }

        if ($finishedJobs.ContainsKey($jobKey)) {
            continue
        }

        if ($runningJobs.ContainsKey($jobKey)) {
            $job = $runningJobs[$jobKey]
            if ($job.State -eq 'Running') {
                continue
            } else {
                Remove-Job $job -Force
                $runningJobs.Remove($jobKey)
                continue
            }
        }

        # ================== JOB ==================

        $scriptBlock = {
            param($fullLine, $logPath, $MAX_RETRIES, $SAFE_START_TIME)

            $parts = $fullLine -split '\s+'
            $args  = $parts[1..($parts.Count - 1)]

            $safeName  = ($fullLine -replace '[^a-zA-Z0-9]', '_')
            $logFile   = Join-Path $logPath "$safeName.log"
            $blockFile = Join-Path $logPath "$safeName.blocked"

            $attempt = 0

            function Write-Log {
                param($level, $message)
                $ts = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
                "$ts [$level] $message" | Out-File $logFile -Append -Encoding UTF8
            }

            Write-Log "INFO" "Preparando ejecucion..."

            while ($attempt -lt $MAX_RETRIES) {

                $attempt++
                Write-Log "INFO" "Intento $attempt de $MAX_RETRIES"

                try {
                    $pinfo = New-Object System.Diagnostics.ProcessStartInfo
                    $pinfo.FileName = "dotnet"
                    $pinfo.Arguments = $args -join " "
                    $pinfo.RedirectStandardOutput = $true
                    $pinfo.RedirectStandardError  = $true
                    $pinfo.UseShellExecute = $false
                    $pinfo.CreateNoWindow = $true

                    $process = New-Object System.Diagnostics.Process
                    $process.StartInfo = $pinfo

                    if (-not $process.Start()) {
                        throw "No se pudo iniciar el proceso"
                    }

                    Write-Log "INFO" "Proceso iniciado. PID: $($process.Id)"
                    Write-Log "INFO" "Comando: dotnet $($args -join ' ')"

                    $startTime = Get-Date

                    while (-not $process.HasExited) {
                        $line = $process.StandardOutput.ReadLine()
                        if ($line) { Write-Log "INFO" $line }
                        Start-Sleep -Milliseconds 100
                    }

                    $process.StandardOutput.ReadToEnd() | ForEach-Object {
                        if ($_){ Write-Log "INFO" $_ }
                    }

                    $process.StandardError.ReadToEnd() | ForEach-Object {
                        if ($_){ Write-Log "ERROR" $_ }
                    }

                    # ===== CORRECCION CLAVE =====
                    $duration = ((Get-Date) - $startTime).TotalSeconds
                    Write-Log "INFO" "El proceso vivio por $([math]::Round($duration,2)) segundos"

                    if ($duration -ge $SAFE_START_TIME) {
                        Write-Log "INFO" "Inicio valido detectado"
                        Write-Log "INFO" "Intentos reiniciados a 0 de $MAX_RETRIES"
                        return
                    }

                    throw "Proceso murio antes del inicio seguro ($SAFE_START_TIME segundos)"

                } catch {
                    Write-Log "ERROR" $_
                }

                if ($attempt -lt $MAX_RETRIES) {
                    Write-Log "INFO" "Reintentando en 10 segundos..."
                    Start-Sleep -Seconds 10
                }
            }

            Write-Log "ERROR" "Proceso abortado tras $MAX_RETRIES intentos consecutivos"
            New-Item -ItemType File -Path $blockFile -Force | Out-Null
        }

        $job = Start-Job -ScriptBlock $scriptBlock -ArgumentList $jobKey, $logFolder, $MAX_RETRIES, $SAFE_START_TIME
        $runningJobs[$jobKey] = $job
    }

    if ($blockedJobs.Count -gt 0) {
        Write-Host "---- PROCESOS BLOQUEADOS ----" -ForegroundColor Yellow
        foreach ($k in $blockedJobs.Keys) {
            Write-Host $k -ForegroundColor Red
        }
    }

    Start-Sleep -Seconds 30
}
