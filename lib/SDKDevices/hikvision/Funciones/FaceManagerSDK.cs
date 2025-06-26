using CorsinfSDKHik.NetSDK;
using Microsoft.VisualBasic.FileIO;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading.Tasks;

namespace CorsinfSDKHik.Funciones
{
    public class FaceManagerSDK
    {
        private int m_UserID = -1;
        private int m_lGetFaceCfgHandle = -1;
        private int m_lSetFaceCfgHandle = -1;
        private int m_lCapFaceCfgHandle = -1;
        public int m_SetSuccessFace = -1;
        public String Facecapture(int m_UserID,String userName,string FilePath)
        {
            String msj = "";
            if (m_lCapFaceCfgHandle != -1)
            {
                CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFaceCfgHandle);
                m_lCapFaceCfgHandle = -1;
            }
            CHCNetSDK.NET_DVR_CAPTURE_FACE_COND struCond = new CHCNetSDK.NET_DVR_CAPTURE_FACE_COND();
            struCond.init();
            struCond.dwSize = Marshal.SizeOf(struCond);
            int dwInBufferSize = struCond.dwSize;
            IntPtr ptrStruCond = Marshal.AllocHGlobal(dwInBufferSize);
            Marshal.StructureToPtr(struCond, ptrStruCond, false);
            m_lCapFaceCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserID, CHCNetSDK.NET_DVR_CAPTURE_FACE_INFO, ptrStruCond, dwInBufferSize, null, IntPtr.Zero);
            if (-1 == m_lCapFaceCfgHandle)
            {
                Marshal.FreeHGlobal(ptrStruCond);
                //MessageBox.Show("NET_DVR_CAP_FACE_FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);
                msj = "NET_DVR_CAP_FACE_FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                return msj;
            }

            CHCNetSDK.NET_DVR_CAPTURE_FACE_CFG struFaceCfg = new CHCNetSDK.NET_DVR_CAPTURE_FACE_CFG();
            struFaceCfg.init();
            int dwStatus = 0;
            int dwOutBuffSize = Marshal.SizeOf(struFaceCfg);
            bool Flag = true;
            while (Flag)
            {
                dwStatus = CHCNetSDK.NET_DVR_GetNextRemoteConfig(m_lCapFaceCfgHandle, ref struFaceCfg, dwOutBuffSize);
                switch (dwStatus)
                {
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_SUCCESS://成功读取到数据，处理完本次数据后需调用next
                        String Status = ProcessCapFaceData(ref struFaceCfg, ref Flag, userName,FilePath);
                        msj = Status;
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_NEED_WAIT:
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FAILED:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFaceCfgHandle);
                        //MessageBox.Show("NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);
                        msj = "NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        Flag = false;
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FINISH:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFaceCfgHandle);
                        Flag = false;
                        break;
                    default:
                        //MessageBox.Show("NET_SDK_GET_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);
                        Flag = false;
                        msj = "NET_SDK_GET_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lCapFaceCfgHandle);
                        break;
                }
            }
            Marshal.FreeHGlobal(ptrStruCond);

            return msj;
        }


        private String ProcessCapFaceData(ref CHCNetSDK.NET_DVR_CAPTURE_FACE_CFG struFaceCfg, ref bool flag,String userName,String strpath)
        {
            if (0 == struFaceCfg.dwFacePicSize)
            {
                return "";
            }
            string rutaCarpeta = @""+ strpath + "";

            if (!Directory.Exists(rutaCarpeta))
            {
                Directory.CreateDirectory(rutaCarpeta);
            }

            strpath = strpath + "\\" + userName + ".jpg";
            DateTime dt = DateTime.Now;
            try
            {
                using (FileStream fs = new FileStream(strpath, FileMode.OpenOrCreate))
                {
                    int FaceLen = struFaceCfg.dwFacePicSize;
                    byte[] by = new byte[FaceLen];
                    Marshal.Copy(struFaceCfg.pFacePicBuffer, by, 0, FaceLen);
                    fs.Write(by, 0, FaceLen);
                    fs.Close();
                }

                //pictureBoxFace.Image = Image.FromFile(strpath);
                //textBoxFilePath.Text = string.Format("{0}\\{1}", Environment.CurrentDirectory, strpath);
                /// MessageBox.Show("Capture succeed", "SUCCESSFUL", MessageBoxButtons.OK);

                m_SetSuccessFace = 1;
                return "Capture succeed";

            }
            catch
            {
                flag = false;
                //MessageBox.Show("capature data wrong", "Error", MessageBoxButtons.OK);
                m_SetSuccessFace = -1;
                return "capature data wrong";
            }
        }

        //setear facial con usuario
        public string SetFace(int m_UserId, String CardReaderNo,String  CardNo,String ruta)
        {
            String msj = "";
            if (ruta == "")
            {
                //MessageBox.Show("Please choose human Face path");
                msj = "Please choose human Face path";
                return msj;
            }

         
            CHCNetSDK.NET_DVR_FACE_COND struCond = new CHCNetSDK.NET_DVR_FACE_COND();
            struCond.init();
            struCond.dwSize = Marshal.SizeOf(struCond);
            struCond.dwFaceNum = 1;
            int.TryParse(CardReaderNo.ToString(), out struCond.dwEnableReaderNo);
            byte[] byTemp = System.Text.Encoding.UTF8.GetBytes(CardNo);
            for (int i = 0; i < byTemp.Length; i++)
            {
                struCond.byCardNo[i] = byTemp[i];
            }

            int dwInBufferSize = struCond.dwSize;
            IntPtr ptrstruCond = Marshal.AllocHGlobal(dwInBufferSize);
            Marshal.StructureToPtr(struCond, ptrstruCond, false);
            m_lSetFaceCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserId, CHCNetSDK.NET_DVR_SET_FACE, ptrstruCond, dwInBufferSize, null, IntPtr.Zero);
            if (-1 == m_lSetFaceCfgHandle)
            {
                Marshal.FreeHGlobal(ptrstruCond);
                //MessageBox.Show("NET_DVR_SET_FACE_FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);

                msj ="NET_DVR_SET_FACE_FAIL, ERROR CODE" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                return msj;
            }

            CHCNetSDK.NET_DVR_FACE_RECORD struRecord = new CHCNetSDK.NET_DVR_FACE_RECORD();
            struRecord.init();
            struRecord.dwSize = Marshal.SizeOf(struRecord);

            byte[] byRecordNo = System.Text.Encoding.UTF8.GetBytes(CardNo);
            for (int i = 0; i < byRecordNo.Length; i++)
            {
                struRecord.byCardNo[i] = byRecordNo[i];
            }

            ReadFaceData(ref struRecord,ruta);
            int dwInBuffSize = Marshal.SizeOf(struRecord);
            int dwStatus = 0;

            CHCNetSDK.NET_DVR_FACE_STATUS struStatus = new CHCNetSDK.NET_DVR_FACE_STATUS();
            struStatus.init();
            struStatus.dwSize = Marshal.SizeOf(struStatus);
            int dwOutBuffSize = struStatus.dwSize;
            IntPtr ptrOutDataLen = Marshal.AllocHGlobal(sizeof(int));
            bool Flag = true;
            while (Flag)
            {
                dwStatus = CHCNetSDK.NET_DVR_SendWithRecvRemoteConfig(m_lSetFaceCfgHandle, ref struRecord, dwInBuffSize, ref struStatus, dwOutBuffSize, ptrOutDataLen);
                switch (dwStatus)
                {
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_SUCCESS://成功读取到数据，处理完本次数据后需调用next
                        msj = ProcessSetFaceData(ref struStatus, ref Flag);
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_NEED_WAIT:
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FAILED:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFaceCfgHandle);
                        //MessageBox.Show("NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);
                        msj = "NET_SDK_GET_NEXT_STATUS_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        Flag = false;
                        break;
                    case CHCNetSDK.NET_SDK_GET_NEXT_STATUS_FINISH:
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFaceCfgHandle);
                        Flag = false;
                        break;
                    default:
                        //MessageBox.Show("NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);
                        msj = "NET_SDK_GET_NEXT_STATUS_UNKOWN" + CHCNetSDK.NET_DVR_GetLastError().ToString();
                        Flag = false;
                        CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetFaceCfgHandle);
                        break;
                }
            }

            Marshal.FreeHGlobal(ptrstruCond);
            Marshal.FreeHGlobal(ptrOutDataLen);
            return msj;
        }

        private void ReadFaceData(ref CHCNetSDK.NET_DVR_FACE_RECORD struRecord,String FilePath)
        {
            String msj = "";
            if (!File.Exists(FilePath))
            {
                //MessageBox.Show("The face picture does not exist!");
                msj = "The face picture does not exist!";
                return;
            }
            FileStream fs = new FileStream(FilePath, FileMode.OpenOrCreate);
            if (0 == fs.Length)
            {
                //MessageBox.Show("The face picture is 0k,please input another picture!");
                msj = "The face picture is 0k,please input another picture!";
                return;
            }
            if (200 * 1024 < fs.Length)
            {
                //MessageBox.Show("The face picture is larger than 200k,please input another picture!");
                msj = "The face picture is larger than 200k,please input another picture!";
                return;
            }
            try
            {
                int.TryParse(fs.Length.ToString(), out struRecord.dwFaceLen);
                int iLen = struRecord.dwFaceLen;
                byte[] by = new byte[iLen];
                struRecord.pFaceBuffer = Marshal.AllocHGlobal(iLen);
                fs.Read(by, 0, iLen);
                Marshal.Copy(by, 0, struRecord.pFaceBuffer, iLen);
                fs.Close();
                //textBoxFilePath.Text = "";
            }
            catch
            {
                //MessageBox.Show("Read Face Data failed");
                msj = "Read Face Data failed";
                fs.Close();
                return;
            }
        }

        private string ProcessSetFaceData(ref CHCNetSDK.NET_DVR_FACE_STATUS struStatus, ref bool flag)
        {
            switch (struStatus.byRecvStatus)
            {
                case 1:
                    //MessageBox.Show("SetFaceDataSuccessful", "Succeed", MessageBoxButtons.OK);
                    m_SetSuccessFace = 1;
                    return "SetFaceDataSuccessful";
                    break;
                default:
                    flag = false;
                    m_SetSuccessFace = -1;
                    //MessageBox.Show("NET_SDK_SET_Face_DATA_FAILED" + struStatus.byRecvStatus.ToString(), "ERROR", MessageBoxButtons.OK);
                    return "NET_SDK_SET_Face_DATA_FAILED" + struStatus.byRecvStatus.ToString();
                    break;
            }

        }


        //DeleteDirectoryOption facial
        public String DeleteFace(int m_UserId,String CardReaderNo,String CardNo)
        {                       
            CHCNetSDK.NET_DVR_FACE_PARAM_CTRL_CARDNO struCardNo = new CHCNetSDK.NET_DVR_FACE_PARAM_CTRL_CARDNO();
            struCardNo.init();
            struCardNo.dwSize = Marshal.SizeOf(struCardNo);
            struCardNo.byMode = 0;
            int dwSize = struCardNo.dwSize;
            byte[] byCardNo = System.Text.Encoding.UTF8.GetBytes(CardNo);
            for (int i = 0; i < byCardNo.Length; i++)
            {
                struCardNo.struByCard.byCardNo[i] = byCardNo[i];
            }

            int dwEnableReaderNo = 1;
            int.TryParse(CardReaderNo, out dwEnableReaderNo);
            if (dwEnableReaderNo <= 0) dwEnableReaderNo = 1;

            struCardNo.struByCard.byEnableCardReader[dwEnableReaderNo - 1] = 1;

            for (int i = 0; i < CHCNetSDK.MAX_FACE_NUM; ++i)
            {
                struCardNo.struByCard.byFaceID[i] = 1;//全部写1删除人脸
            }

            if (false == CHCNetSDK.NET_DVR_RemoteControl(m_UserId, CHCNetSDK.NET_DVR_DEL_FACE_PARAM_CFG, ref struCardNo, dwSize))
            {
                //MessageBox.Show("NET_SDK_DEL_FACE_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString(), "Error", MessageBoxButtons.OK);
                m_SetSuccessFace = -1;
                return "NET_SDK_DEL_FACE_FAILED" + CHCNetSDK.NET_DVR_GetLastError().ToString();
            }
            else
            {
                //MessageBox.Show("NET_SDK_DEL_FACE_SUCCEED", "succeed", MessageBoxButtons.OK);
                m_SetSuccessFace = 1;
                return "NET_SDK_DEL_FACE_SUCCEED";
            }
        }

    }
}
