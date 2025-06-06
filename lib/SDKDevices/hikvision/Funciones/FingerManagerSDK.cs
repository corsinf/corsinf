using CorsinfSDKHik.ConfigDB;
using CorsinfSDKHik.NetSDK;
using Lextm.SharpSnmpLib.Security;
using Microsoft.Data.SqlClient;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection.Metadata.Ecma335;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Funciones
{

    public class FingerManagerSDK
    {
        public int m_lGetFingerPrintCfgHandle = -1;
        public int m_lSetFingerPrintCfgHandle = -1;
        public int m_lDelFingerPrintCfHandle = -1;
        public int m_lCapFingerPrintCfHandle = -1;
        public String FingerData = null;
        public int m_SetSuccessFing = -1;
        public int m_SaveSuccessFing = -1;


        public static CHCNetSDK.MSGCallBack m_falarmData = null;
        public static string path = null;

        public static SqlConnection conn_ = null;

        public String SetearFinger(int m_UserID,String FingerID,String CardReaderNo,String CardNo,String ruta)
        {
            String msj = "";
            FingerData = ruta;
            if (m_lSetFingerPrintCfgHandle != -1)
            {
                CHCNetSDK.NET_DVR_StopRemoteConfig((int)m_lSetFingerPrintCfgHandle);
                m_lSetFingerPrintCfgHandle = -1;
            }

            CHCNetSDK.NET_DVR_FINGERPRINT_CONDF strupond = new CHCNetSDK.NET_DVR_FINGERPRINT_CONDF();
            strupond.init();
            int dwSize = Marshal.SizeOf(strupond);
            strupond.dwSize = dwSize;
            byte.TryParse(FingerID, out strupond.byFingerPrintID);
            int.TryParse(CardReaderNo, out strupond.dwEnableReaderNo);
            strupond.dwFingerprintNum = 5;//指纹数量写死的
            byte[] byTempptrRec = System.Text.Encoding.UTF8.GetBytes(CardNo);
            for (int i = 0; i < byTempptrRec.Length; i++)
            {
                strupond.byCardNo[i] = byTempptrRec[i];
            }

            IntPtr ptrStrucond = Marshal.AllocHGlobal(dwSize);
            Marshal.StructureToPtr(strupond, ptrStrucond, false);

            m_lSetFingerPrintCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserID, CHCNetSDK.NET_DVR_SET_FINGERPRINT, ptrStrucond, dwSize, null, IntPtr.Zero);
            if (-1 == m_lSetFingerPrintCfgHandle)
            {
                Marshal.FreeHGlobal(ptrStrucond);
                //Console.WriteLine("NET_DVR_SET_FINGERPRINT_CFG_V50 FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                msj = "NET_DVR_SET_FINGERPRINT_CFG_V50 FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                return msj;
            }

            Boolean Flag = true;
            int dwStatus = 0;
            CHCNetSDK.NET_DVR_FINGERPRINT_RECORDF StruRecord = new CHCNetSDK.NET_DVR_FINGERPRINT_RECORDF();
            StruRecord.init();
            int dwInBuffSize = Marshal.SizeOf(StruRecord);
            StruRecord.dwSize = dwInBuffSize;
            byte.TryParse(FingerID, out StruRecord.byFingerPrintID);
            int.TryParse(CardReaderNo, out StruRecord.dwEnableReaderNo);

            byte[] byTemp = System.Text.Encoding.UTF8.GetBytes(CardNo);
            for (int i = 0; i < byTemp.Length; i++)
            {
                StruRecord.byCardNo[i] = byTemp[i];
            }
            String Resp = ReadFingerData(ref StruRecord);
            if (Resp != "")
            {
                return Resp;
            }

            CHCNetSDK.NET_DVR_FINGERPRINT_STATUSF StruStatus = new CHCNetSDK.NET_DVR_FINGERPRINT_STATUSF();
            StruStatus.init();
            int dwOutBuffSize = Marshal.SizeOf(StruStatus);
            StruStatus.dwSize = dwOutBuffSize;
            IntPtr ptrOutDataLen = Marshal.AllocHGlobal(sizeof(int));
            while (Flag)
            {
                dwStatus = CHCNetSDK.NET_DVR_SendWithRecvRemoteConfig(m_lSetFingerPrintCfgHandle, ref StruRecord, dwInBuffSize, ref StruStatus, dwOutBuffSize, ptrOutDataLen);
                switch (dwStatus)
                {
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_SUCCESS://成功读取到数据，处理完本次数据后需调用next
                        msj = ProcessSetFingerData(ref StruStatus, ref Flag);
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_NEED_WAIT:
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FAILED:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFingerPrintCfgHandle);
                        //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                        msj = "NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        m_SetSuccessFing = -1;
                        Flag = false;
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FINISH:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFingerPrintCfgHandle);
                        //msj = "Finalizado sin razon";
                        //m_SetSuccessFing = -1;
                        Flag = false;
                        break;
                    default:
                        //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                        msj = "NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        m_SetSuccessFing = -1;
                        Flag = false;
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFingerPrintCfgHandle);
                        break;
                }
            }
            Marshal.FreeHGlobal(ptrStrucond);
            Marshal.FreeHGlobal(ptrOutDataLen);
            return msj;
        }
        private String ReadFingerData(ref CHCNetSDK.NET_DVR_FINGERPRINT_RECORDF Record)
        {
            String msj = "";
            try
            {
                using (FileStream fs = new FileStream(FingerData, FileMode.OpenOrCreate))
                {
                    if (0 == fs.Length)
                    {
                        Record.byFingerData[0] = 0;
                        fs.Close();
                    }
                    Record.dwFingerPrintLen = (int)fs.Length;
                    BinaryReader objBinaryReader = new BinaryReader(fs);
                    if (Record.dwFingerPrintLen > CHCNetSDK.MAX_FINGER_PRINT_LEN)
                    {
                        //Console.WriteLine("FingerPrintLen is too long");
                        msj = "FingerPrintLen is too long";
                        return msj;
                    }
                    for (int i = 0; i < Record.dwFingerPrintLen; i++)
                    {
                        if (i >= fs.Length)
                        {
                            break;
                        }
                        Record.byFingerData[i] = objBinaryReader.ReadByte();
                    }
                    fs.Close();
                }
                return msj;
            }
            catch
            {
                if (m_lSetFingerPrintCfgHandle != -1)
                {
                    CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFingerPrintCfgHandle);
                }
                //Console.WriteLine("FingerDataPath may be wrong");
                msj = "FingerDataPath may be wrong";
                return msj;
            }

        }
        private String ProcessSetFingerData(ref CHCNetSDK.NET_DVR_FINGERPRINT_STATUSF ststus, ref bool flag)
        {
            String msj = "";
            switch (ststus.byRecvStatus)
            {
                case 0:
                    //Console.WriteLine("SetFingegDataSuccessful", "Succeed");
                    msj = "SetFingegDataSuccessful";
                    m_SetSuccessFing = 1;
                    break;
                default:
                    flag = false;
                    //Console.WriteLine("NET_SDK_SET_FINGER_DATA_FAILED" + ststus.byRecvStatus.ToString());
                    msj = "NET_SDK_SET_FINGER_DATA_FAILED" + ststus.byRecvStatus.ToString();
                    m_SetSuccessFing = -1;
                    break;
            }

            return msj;
        }

        //tomar datos de biometricos

        public string capturarDedo(int m_UserID, string userName, string patch)
        {
            String msj = "";
            if (m_lCapFingerPrintCfHandle != -1)
            {
                CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFingerPrintCfHandle);
                m_lCapFingerPrintCfHandle = -1;
            }

            CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_COND struCond = new CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_COND();
            struCond.init();
            struCond.dwSize = Marshal.SizeOf(struCond);
            int dwInBufferSize = struCond.dwSize;
            struCond.byFingerPrintPicType = 1; //指纹图片类型是什么暂定1
            struCond.byFingerNo = 1;
            IntPtr ptrStruCond = Marshal.AllocHGlobal(struCond.dwSize);
            Marshal.StructureToPtr(struCond, ptrStruCond, false);

            m_lCapFingerPrintCfHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserID, CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_INFO, ptrStruCond, dwInBufferSize, null, IntPtr.Zero);
            if (-1 == m_lCapFingerPrintCfHandle)
            {
                Marshal.FreeHGlobal(ptrStruCond);
                Console.WriteLine("NET_DVR_CAP_FINGERPRINT FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString());
            }

            bool flag = true;
            int dwStatus = 0;

            CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_CFG struCFG = new CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_CFG();
            struCFG.init();
            struCFG.dwSize = Marshal.SizeOf(struCFG);
            int dwOutBuffSize = struCFG.dwSize;
            while (flag)
            {
                dwStatus = CHCNetSDK.NET_DVR_GetNextRemoteConfig(m_lCapFingerPrintCfHandle, ref struCFG, dwOutBuffSize);
                switch (dwStatus)
                {
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_SUCCESS://成功读取到数据，处理完本次数据后需调用next
                        msj = ProcessCapFingerData(ref struCFG, ref flag, userName, patch);
                        if(m_SaveSuccessFing==-1)
                        {
                            return msj;
                        }
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_NEED_WAIT:
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FAILED:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFingerPrintCfHandle);
                        //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                        flag = false;
                        msj = "NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FINISH:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFingerPrintCfHandle);
                        flag = false;
                        break;
                    default:
                        //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                        msj = "NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        flag = false;
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFingerPrintCfHandle);
                        break;
                }
            }
            Marshal.FreeHGlobal(ptrStruCond);

            return msj;
        }

        private String ProcessCapFingerData(ref CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_CFG struCFG, ref bool flag, string userName, string strpath = null)
        {
            DateTime dt = DateTime.Now;
            strpath = strpath + "\\" + userName + ".dat";
            try
            {
                using (FileStream fs = new FileStream(strpath, FileMode.OpenOrCreate))
                {
                    fs.Write(struCFG.byFingerData, 0, struCFG.dwFingerPrintDataSize);
                    fs.Close();
                }
                nint bits = struCFG.pFingerPrintPicBuffer;
                //Console.WriteLine("Huella guardada");
                m_SaveSuccessFing = 1;
                return "Huella guardada";
            }
            catch
            {
                //Console.WriteLine("CapFingerprint process failed");
                flag = false;
                return "No se pudo guardar en la ruta:"+ strpath;
            }
        }

        // eveto de escuicha de huellas digitales 
        public String Escuchando(int m_UserID,SqlConnection conn)
        {
            conn_ = conn;
            String msj = "";
            CHCNetSDK.NET_DVR_SETUPALARM_PARAM struSetupAlarmParam = new CHCNetSDK.NET_DVR_SETUPALARM_PARAM();
            struSetupAlarmParam.dwSize = (uint)Marshal.SizeOf(struSetupAlarmParam);
            struSetupAlarmParam.byLevel = 1;
            struSetupAlarmParam.byAlarmInfoType = 1;
            struSetupAlarmParam.byDeployType = 1; //este es para detectar

            if (CHCNetSDK.NET_DVR_SetupAlarmChan_V41(m_UserID, ref struSetupAlarmParam) < 0)
            {
                //Console.WriteLine("Error al configurar el canal de alarma: " + CHCNetSDK.NET_DVR_GetLastError(), "Setup alarm chan failed");
                msj = "Error al configurar el canal de alarma: " + CHCNetSDK.NET_DVR_GetLastError() + "Setup alarm chan failed";
                return msj;
            }
            else
            {
                //Console.WriteLine("Configuración del canal de alarma exitosa.");
            }

            m_falarmData = new CHCNetSDK.MSGCallBack(MsgCallback);
            if (CHCNetSDK.NET_DVR_SetDVRMessageCallBack_V50(0, m_falarmData, IntPtr.Zero))
            {
               // Console.WriteLine("Callback registrado exitosamente.");
            }
            else
            {
                //Console.WriteLine("Error al registrar el callback.");
                msj = "Error al registrar el callback.";
                return msj;
            }

            // Mantener el proceso en ejecución
            //Console.WriteLine("Escuchando alarmas...");
            while (true)
            {
                // Puedes agregar una pequeña pausa para evitar un uso excesivo de CPU
                Thread.Sleep(1000);
            }
        }

        public void MsgCallback(int lCommand, ref CHCNetSDK.NET_DVR_ALARMER pAlarmer, IntPtr pAlarmInfo, uint dwBufLen, IntPtr pUser)
        {
            String msj;
            Modelo dbModelo = new Modelo();
            switch (lCommand)
            {
                case CHCNetSDK.COMM_ALARM_ACS:
                    msj = ProcessCommAlarmACS(ref pAlarmer, pAlarmInfo, dwBufLen, pUser);
                    dbModelo.InsertData(conn_,msj);
                  //  Console.WriteLine(msj);
                    break;
                default:
                    break;
            }

           
        }

        public String ProcessCommAlarmACS(ref CHCNetSDK.NET_DVR_ALARMER pAlarmer, IntPtr pAlarmInfo, uint dwBufLen, IntPtr pUser)
        {
            String msj = "";
            CHCNetSDK.NET_DVR_ACS_ALARM_INFO struAcsAlarmInfo = new CHCNetSDK.NET_DVR_ACS_ALARM_INFO();
            struAcsAlarmInfo = (CHCNetSDK.NET_DVR_ACS_ALARM_INFO)Marshal.PtrToStructure(pAlarmInfo, typeof(CHCNetSDK.NET_DVR_ACS_ALARM_INFO));
            CHCNetSDK.NET_DVR_LOG_V30 struFileInfo = new CHCNetSDK.NET_DVR_LOG_V30();
            struFileInfo.dwMajorType = struAcsAlarmInfo.dwMajor;
            struFileInfo.dwMinorType = struAcsAlarmInfo.dwMinor;
            char[] csTmp = new char[256];


            CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_COND struCond = new CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_COND();
            struCond.init();
            struCond.dwSize = Marshal.SizeOf(struCond);
            int dwInBufferSize = struCond.dwSize;
            struCond.byFingerPrintPicType = 1; //指纹图片类型是什么暂定1
            struCond.byFingerNo = 1;
            IntPtr ptrStruCond = Marshal.AllocHGlobal(struCond.dwSize);
            Marshal.StructureToPtr(struCond, ptrStruCond, false);


            CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_CFG struCFG = new CHCNetSDK.NET_DVR_CAPTURE_FINGERPRINT_CFG();
            struCFG.init();
            struCFG.dwSize = Marshal.SizeOf(struCFG);
            int dwOutBuffSize = struCFG.dwSize;



            if (CHCNetSDK.MAJOR_ALARM == struFileInfo.dwMajorType)
            {
                TypeMap.AlarmMinorTypeMap(struFileInfo, csTmp);
            }
            else if (CHCNetSDK.MAJOR_OPERATION == struFileInfo.dwMajorType)
            {
                TypeMap.OperationMinorTypeMap(struFileInfo, csTmp);
            }
            else if (CHCNetSDK.MAJOR_EXCEPTION == struFileInfo.dwMajorType)
            {
                TypeMap.ExceptionMinorTypeMap(struFileInfo, csTmp);
            }
            else if (CHCNetSDK.MAJOR_EVENT == struFileInfo.dwMajorType)
            {
                TypeMap.EventMinorTypeMap(struFileInfo, csTmp);
            }

            String szInfo = new String(csTmp).TrimEnd('\0');
            String szInfoBuf = null;
            szInfoBuf = szInfo;
            /**************************************************/
            String name = System.Text.Encoding.UTF8.GetString(struAcsAlarmInfo.sNetUser).TrimEnd('\0');
            for (int i = 0; i < struAcsAlarmInfo.sNetUser.Length; i++)
            {
                if (struAcsAlarmInfo.sNetUser[i] == 0)
                {
                    name = name.Substring(0, i);
                    break;
                }
            }
            /**************************************************/


            msj = "[{\"ip\":\"" + pAlarmer.sDeviceIP + "\",";

            szInfoBuf = string.Format("{0} time:{1,4}-{2:D2}-{3} {4:D2}:{5:D2}:{6:D2}, [{7}]({8})", szInfo, struAcsAlarmInfo.struTime.dwYear, struAcsAlarmInfo.struTime.dwMonth,
                struAcsAlarmInfo.struTime.dwDay, struAcsAlarmInfo.struTime.dwHour, struAcsAlarmInfo.struTime.dwMinute, struAcsAlarmInfo.struTime.dwSecond,
                struAcsAlarmInfo.struRemoteHostAddr.sIpV4, name);

            if (struAcsAlarmInfo.struAcsEventInfo.byCardNo[0] != 0)
            {
                msj += "\"Card Number\":\"" + System.Text.Encoding.UTF8.GetString(struAcsAlarmInfo.struAcsEventInfo.byCardNo).TrimEnd('\0')+ "\",";
            }
            String[] szCardType = { "normal card", "disabled card", "blocklist card", "night watch card", "stress card", "super card", "guest card" };
            byte byCardType = struAcsAlarmInfo.struAcsEventInfo.byCardType;

            if (byCardType != 0 && byCardType <= szCardType.Length)
            {
                // szInfoBuf = szInfoBuf + "+Card Type:" + szCardType[byCardType - 1];
                msj += "\"cardType\":\"" + szCardType[byCardType - 1] + "\",";
            }

            if (struAcsAlarmInfo.struAcsEventInfo.dwCardReaderNo != 0)
            {
                szInfoBuf = szInfoBuf + "+Card Reader Number:" + struAcsAlarmInfo.struAcsEventInfo.dwCardReaderNo;
                msj += "\"cardReaderNumber\":\"" + struAcsAlarmInfo.struAcsEventInfo.dwCardReaderNo + "\",";
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwDoorNo != 0)
            {
                // szInfoBuf = szInfoBuf + "+Door Number:" + struAcsAlarmInfo.struAcsEventInfo.dwDoorNo;
                msj += "\"doorNumber\":\"" + struAcsAlarmInfo.struAcsEventInfo.dwDoorNo + "\",";
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwVerifyNo != 0)
            {
                szInfoBuf = szInfoBuf + "+Multiple Card Authentication Serial Number:" + struAcsAlarmInfo.struAcsEventInfo.dwVerifyNo;
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwAlarmInNo != 0)
            {
                // szInfoBuf = szInfoBuf + "+Alarm Input Number:" + struAcsAlarmInfo.struAcsEventInfo.dwAlarmInNo;
                msj += "\"alarmNumber\":\"" + szCardType[byCardType - 1] + "\",";

            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwAlarmOutNo != 0)
            {
                szInfoBuf = szInfoBuf + "+Alarm Output Number:" + struAcsAlarmInfo.struAcsEventInfo.dwAlarmOutNo;
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwCaseSensorNo != 0)
            {
                szInfoBuf = szInfoBuf + "+Event Trigger Number:" + struAcsAlarmInfo.struAcsEventInfo.dwCaseSensorNo;
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwRs485No != 0)
            {
                szInfoBuf = szInfoBuf + "+RS485 Channel Number:" + struAcsAlarmInfo.struAcsEventInfo.dwRs485No;
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwMultiCardGroupNo != 0)
            {
                szInfoBuf = szInfoBuf + "+Multi Recombinant Authentication ID:" + struAcsAlarmInfo.struAcsEventInfo.dwMultiCardGroupNo;
            }
            if (struAcsAlarmInfo.struAcsEventInfo.byCardReaderKind != 0)
            {
                szInfoBuf = szInfoBuf + "+CardReaderKind:" + struAcsAlarmInfo.struAcsEventInfo.byCardReaderKind.ToString();
            }
            if (struAcsAlarmInfo.struAcsEventInfo.wAccessChannel >= 0)
            {
                //szInfoBuf = szInfoBuf + "+wAccessChannel:" + struAcsAlarmInfo.struAcsEventInfo.wAccessChannel;
                msj += "\"accessChannel\":\"" + struAcsAlarmInfo.struAcsEventInfo.wAccessChannel + "\",";
            }
            if (struAcsAlarmInfo.struAcsEventInfo.dwEmployeeNo != 0)
            {
                szInfoBuf = szInfoBuf + "+EmployeeNo:" + struAcsAlarmInfo.struAcsEventInfo.dwEmployeeNo;
            }     
            if (struAcsAlarmInfo.struAcsEventInfo.byDeviceNo != 0)
            {
                szInfoBuf = szInfoBuf + "+byDeviceNo:" + struAcsAlarmInfo.struAcsEventInfo.byDeviceNo.ToString();
            }
            if (struAcsAlarmInfo.struAcsEventInfo.wLocalControllerID >= 0)
            {
                //szInfoBuf = szInfoBuf + "+wLocalControllerID:" + struAcsAlarmInfo.struAcsEventInfo.wLocalControllerID;
                msj += "\"localControllerID\":\"" + struAcsAlarmInfo.struAcsEventInfo.wLocalControllerID + "\",";
            }
            if (struAcsAlarmInfo.struAcsEventInfo.byInternetAccess >= 0)
            {
                //szInfoBuf = szInfoBuf + "+byInternetAccess:" + struAcsAlarmInfo.struAcsEventInfo.byInternetAccess.ToString();
                msj += "\"byInternetAccess\":\"" + struAcsAlarmInfo.struAcsEventInfo.byInternetAccess.ToString() + "\",";

            }
            if (struAcsAlarmInfo.struAcsEventInfo.byType >= 0)
            {
                // szInfoBuf = szInfoBuf + "+byType:" + struAcsAlarmInfo.struAcsEventInfo.byType.ToString();
                msj += "\"byType\":\"" + struAcsAlarmInfo.struAcsEventInfo.byType.ToString() + "\",";
            }
            if (struAcsAlarmInfo.struAcsEventInfo.bySwipeCardType != 0)
            {
                szInfoBuf = szInfoBuf + "+bySwipeCardType:" + struAcsAlarmInfo.struAcsEventInfo.bySwipeCardType.ToString();
            }
            //其它消息先不罗列了......

            if (struAcsAlarmInfo.dwPicDataLen > 0)
            {
                path = null;
                Random rand = new Random(unchecked((int)DateTime.Now.Ticks));
                path = string.Format(@"C:/Picture/ACS_LocalTime{0}_{1}.bmp", szInfo, rand.Next());
                using (FileStream fs = new FileStream(path, FileMode.Create))
                {
                    int iLen = (int)struAcsAlarmInfo.dwPicDataLen;
                    byte[] by = new byte[iLen];
                    Marshal.Copy(struAcsAlarmInfo.pPicData, by, 0, iLen);
                    fs.Write(by, 0, iLen);
                    fs.Close();
                }
                szInfoBuf = szInfoBuf + "SavePath:" + path;
            }

            msj += "\"fecha\":\"" + DateTime.Now.ToString() + "\"}]";
            //xmlDoc.LoadXml(receivedXml); // Cargar el XML en XmlDocument
            //string jsonText = JsonConvert.SerializeXmlNode(xmlDoc); // Convertir a JSON             
            //Console.WriteLine(jsonText);

            return msj;

        }

        // Función para obtener huellas de un usuario
        public string GetRegisteredFingerprintIDs(int m_UserID, string cardNo,string dispositivo = "1")
        {

            String ListaItemHuella = "[";
            for (int item = 0; item < 20; item++) // puedes ajustar el límite según tu entorno
            {
                if (m_lGetFingerPrintCfgHandle != -1)
                {
                    CHCNetSDK.NET_DVR_StopRemoteConfig((int)m_lGetFingerPrintCfgHandle);
                    m_lGetFingerPrintCfgHandle = -1;
                }

                CHCNetSDK.NET_DVR_FINGERPRINT_CONDF struCond = new CHCNetSDK.NET_DVR_FINGERPRINT_CONDF();
                struCond.init();
                struCond.dwSize = Marshal.SizeOf(struCond);
                struCond.dwFingerprintNum = 5;
                byte.TryParse(item.ToString(), out struCond.byFingerPrintID);
                byte[] byTemp = System.Text.Encoding.UTF8.GetBytes(cardNo);
                for (int i = 0; i < byTemp.Length; i++)
                {
                    struCond.byCardNo[i] = byTemp[i];
                }
                int.TryParse(dispositivo, out struCond.dwEnableReaderNo);
                int dwSize = Marshal.SizeOf(struCond);
                IntPtr ptrStruCond = Marshal.AllocHGlobal(dwSize);
                Marshal.StructureToPtr(struCond, ptrStruCond, false);
                m_lGetFingerPrintCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserID, CHCNetSDK.NET_DVR_GET_FINGERPRINT, ptrStruCond, dwSize, null, IntPtr.Zero);
                if (-1 == m_lGetFingerPrintCfgHandle)
                {
                    Marshal.FreeHGlobal(ptrStruCond);
                    //Console.WriteLine("NET_DVR_GET_FINGERPRINT_CFG_V50 FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                    continue;
                }
                else
                {

                    Boolean Flag = true;
                    CHCNetSDK.NET_DVR_FINGERPRINT_RECORDF struOutBuff = new CHCNetSDK.NET_DVR_FINGERPRINT_RECORDF();
                    struOutBuff.init();
                    int dWsize = Marshal.SizeOf(struOutBuff);
                    int dwStatus = 0;

                    while (Flag)
                    {
                        dwStatus = CHCNetSDK.NET_DVR_GetNextRemoteConfig(m_lGetFingerPrintCfgHandle, ref struOutBuff, dWsize);
                        switch (dwStatus)
                        {
                            case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_SUCCESS:
                                //成功读取到数据，处理完本次数据后需调用next
                                //ProcessFingerData(ref struOutBuff, ref Flag);
                                ListaItemHuella += "{\"item\":\"" + item.ToString() + "\"},";
                                m_SetSuccessFing = 1;
                                //Console.WriteLine("Huella encontrada en item:" + item);
                                break;
                            case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_NEED_WAIT:
                                break;
                            case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FAILED:
                                CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetFingerPrintCfgHandle);
                                //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                                Flag = false;
                                break;
                            case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FINISH:
                                //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_FINISH");
                                CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetFingerPrintCfgHandle);
                                Flag = false;
                                break;
                            default:
                                //Console.WriteLine("NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString());
                                CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetFingerPrintCfgHandle);
                                Flag = false;
                                break;
                        }
                    }
                }
                Marshal.FreeHGlobal(ptrStruCond);
            }

            if (ListaItemHuella != "[")
            {

                ListaItemHuella = ListaItemHuella.Substring(0, ListaItemHuella.Length - 1);
                ListaItemHuella = ListaItemHuella + "]";
            }
            else { ListaItemHuella = ""; }

            return ListaItemHuella;
        }
       
        private void ProcessFingerData(ref CHCNetSDK.NET_DVR_FINGERPRINT_RECORDF struOutBuff, ref bool flag)
        {
            string strpath = null;
            DateTime dt = DateTime.Now;
            strpath = string.Format("{0}\\fingerprint.dat", Environment.CurrentDirectory);
            try
            {
                using (FileStream fs = new FileStream(strpath, FileMode.OpenOrCreate))
                {
                    if (!File.Exists(strpath))
                    {
                        Console.WriteLine("Fingerprint storage file creat failed！");
                    }
                    BinaryWriter objBinaryWrite = new BinaryWriter(fs);
                    fs.Write(struOutBuff.byFingerData, 0, struOutBuff.dwFingerPrintLen);
                    fs.Close();
                }
                //textBoxFingerData.Text = strpath;
                Console.WriteLine("Fingerprint GET SUCCEED");
            }
            catch
            {
                Console.WriteLine("Fingerprint process failed");
                flag = false;
            }
        }


    }
}
