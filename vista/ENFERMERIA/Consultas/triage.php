<div>
    <div class="row pt-2">
        <div class="col-md-3">
            <label for="" class="form-label fw-bold">Peso (kg) <label style="color: red;"></label></label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm solo_numeros" id="sa_conp_peso" name="sa_conp_peso" placeholder="Ejemplo: 68.45 (Kilogramos)">
        </div>
        <div class="col-md-3">
            <label for="" class="form-label fw-bold">Altura (m) <label style="color: red;"></label> </label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm" id="sa_conp_altura" name="sa_conp_altura" placeholder="Ejemplo: 1.45 (Metros)">
        </div>
        <div class="col-md-3">
            <label for="" class="form-label fw-bold">IMC <label style="color: red;"></label> </label>
            <input type="number" min="1" maxlength="8" class="form-control form-control-sm solo_numeros" id="txt_imc" name="txt_imc" readonly disabled>
        </div>
        <div class="col-md-3">
            <label for="" class="form-label fw-bold">Nivel del Peso <label style="color: red;"></label> </label>
            <input type="text" class="form-control form-control-sm solo_numeros" id="txt_np" name="txt_np" readonly disabled>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col-md-2">
            <label for="" class="form-label fw-bold">Temperatura (°C) <label style="color: red;"></label> </label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm solo_numeros" id="sa_conp_temperatura" name="sa_conp_temperatura">
        </div>
        <div class="col-md-2">
            <label for="" class="form-label fw-bold">Presión Arterial (mmHg) <label style="color: red;"></label> </label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm solo_numeros_slash" id="sa_conp_presion_ar" name="sa_conp_presion_ar">
        </div>
        <div class="col-md-2">
            <label for="" class="form-label fw-bold">Saturación (SpO2 %) <label style="color: red;"></label> </label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm solo_numeros" id="sa_conp_saturacion" name="sa_conp_saturacion">
        </div>
        <div class="col-md-3">
            <label for="" class="form-label fw-bold">Frecuencia Cardiáca (lpm) <label style="color: red;"></label> </label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm solo_numeros" id="sa_conp_frec_cardiaca" name="sa_conp_frec_cardiaca">
        </div>
        <div class="col-md-3">
            <label for="" class="form-label fw-bold">Frecuencia Respiratoria (rpm) <label style="color: red;"></label> </label>
            <input type="text" min="1" maxlength="8" class="form-control form-control-sm solo_numeros" id="sa_conp_frec_respiratoria" name="sa_conp_frec_respiratoria">
        </div>
    </div>

    <div class="row pt-3">
        <div class="col-md-12">
            <label for="" class="form-label fw-bold">Motivo de la consulta <label style="color: red;">*</label> </label>
            <textarea name="sa_conp_motivo_consulta" id="sa_conp_motivo_consulta" cols="30" rows="4" class="form-control" placeholder="Motivo de la consulta" maxlength="1000"></textarea>
        </div>
    </div>
</div>