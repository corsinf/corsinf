﻿using CorsinfSDKHik.NetSDK;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace CorsinfSDKHik.Funciones
{
    public class CardManagerSDK
    {

        public static int m_UserID = -1;
        public int m_SetSuccess = -1;
        public Int32 m_lGetCardCfgHandle = -1;
        public Int32 m_lSetCardCfgHandle = -1;
        public Int32 m_lDelCardCfgHandle = -1;

        public CardManagerSDK()
        {
            if (CHCNetSDK.NET_DVR_Init() == false)
            {
                Console.WriteLine("NET_DVR_Init error!");
                return;
            }
            CHCNetSDK.NET_DVR_SetLogToFile(3, "./", false);
        }
        public String SetearCard(int m_UserID, String CardNo, String CardRightPlan, String EmployeeNo, String Name)
        {
            String msj = "";
            if (m_lSetCardCfgHandle != -1)
            {
                if (CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetCardCfgHandle))
                {
                    m_lSetCardCfgHandle = -1;
                }
            }

            CHCNetSDK.NET_DVR_CARD_COND struCond = new CHCNetSDK.NET_DVR_CARD_COND();
            struCond.Init();
            struCond.dwSize = (uint)Marshal.SizeOf(struCond);
            struCond.dwCardNum = 1;
            IntPtr ptrStruCond = Marshal.AllocHGlobal((int)struCond.dwSize);
            Marshal.StructureToPtr(struCond, ptrStruCond, false);

            m_lSetCardCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserID, CHCNetSDK.NET_DVR_SET_CARD, ptrStruCond, (int)struCond.dwSize, null, IntPtr.Zero);
            if (m_lSetCardCfgHandle < 0)
            {
                //Console.WriteLine("NET_DVR_SET_CARD error:" + CHCNetSDK.NET_DVR_GetLastError());
                msj = "NET_DVR_SET_CARD error:" + CHCNetSDK.NET_DVR_GetLastError();
                Marshal.FreeHGlobal(ptrStruCond);
                return msj;
            }
            else
            {
               msj = SendCardData(CardNo,CardRightPlan,EmployeeNo,Name);
                Marshal.FreeHGlobal(ptrStruCond);
            }

            return msj;
        }

        private String SendCardData(String CardNo, String CardRightPlan,String EmployeeNo, String Name)
        {
            String msj = "";
            CHCNetSDK.NET_DVR_CARD_RECORD struData = new CHCNetSDK.NET_DVR_CARD_RECORD();
            struData.Init();
            struData.dwSize = (uint)Marshal.SizeOf(struData);
            struData.byCardType = 1;
            byte[] byTempCardNo = new byte[CHCNetSDK.ACS_CARD_NO_LEN];
            byTempCardNo = System.Text.Encoding.UTF8.GetBytes(CardNo);
            for (int i = 0; i < byTempCardNo.Length; i++)
            {
                struData.byCardNo[i] = byTempCardNo[i];
            }
            ushort.TryParse(CardRightPlan, out struData.wCardRightPlan[0]);
            uint.TryParse(EmployeeNo, out struData.dwEmployeeNo);
            byte[] byTempName = new byte[CHCNetSDK.NAME_LEN];
            byTempName = System.Text.Encoding.Default.GetBytes(Name);
            for (int i = 0; i < byTempName.Length; i++)
            {
                struData.byName[i] = byTempName[i];
            }
            struData.struValid.byEnable = 1;
            struData.struValid.struBeginTime.wYear = 2000;
            struData.struValid.struBeginTime.byMonth = 1;
            struData.struValid.struBeginTime.byDay = 1;
            struData.struValid.struBeginTime.byHour = 11;
            struData.struValid.struBeginTime.byMinute = 11;
            struData.struValid.struBeginTime.bySecond = 11;
            struData.struValid.struEndTime.wYear = 2030;
            struData.struValid.struEndTime.byMonth = 1;
            struData.struValid.struEndTime.byDay = 1;
            struData.struValid.struEndTime.byHour = 11;
            struData.struValid.struEndTime.byMinute = 11;
            struData.struValid.struEndTime.bySecond = 11;
            struData.byDoorRight[0] = 1;
            struData.wCardRightPlan[0] = 1;
            IntPtr ptrStruData = Marshal.AllocHGlobal((int)struData.dwSize);
            Marshal.StructureToPtr(struData, ptrStruData, false);

            CHCNetSDK.NET_DVR_CARD_STATUS struStatus = new CHCNetSDK.NET_DVR_CARD_STATUS();
            struStatus.Init();
            struStatus.dwSize = (uint)Marshal.SizeOf(struStatus);
            IntPtr ptrdwState = Marshal.AllocHGlobal((int)struStatus.dwSize);
            Marshal.StructureToPtr(struStatus, ptrdwState, false);

            int dwState = (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_SUCCESS;
            uint dwReturned = 0;
            while (true)
            {
                dwState = CHCNetSDK.NET_DVR_SendWithRecvRemoteConfig(m_lSetCardCfgHandle, ptrStruData, struData.dwSize, ptrdwState, struStatus.dwSize, ref dwReturned);
                struStatus = (CHCNetSDK.NET_DVR_CARD_STATUS)Marshal.PtrToStructure(ptrdwState, typeof(CHCNetSDK.NET_DVR_CARD_STATUS));
                if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_NEEDWAIT)
                {
                    Thread.Sleep(10);
                    continue;
                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_FAILED)
                {
                    //Console.WriteLine("NET_DVR_SET_CARD fail error: " + CHCNetSDK.NET_DVR_GetLastError());
                    msj = "NET_DVR_SET_CARD fail error: " + CHCNetSDK.NET_DVR_GetLastError();
                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_SUCCESS)
                {
                    if (struStatus.dwErrorCode != 0)
                    {
                        //Console.WriteLine("NET_DVR_SET_CARD success but errorCode:" + struStatus.dwErrorCode);
                        msj = "NET_DVR_SET_CARD success but errorCode:" + struStatus.dwErrorCode;
                    }
                    else
                    {
                        //Console.WriteLine("NET_DVR_SET_CARD success");
                        msj = "NET_DVR_SET_CARD success";
                        m_SetSuccess = 1;
                        //break;
                    }
                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_FINISH)
                {
                    //Console.WriteLine("NET_DVR_SET_CARD finish");
                    msj = "NET_DVR_SET_CARD finish";
                    break;
                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_EXCEPTION)
                {
                    //Console.WriteLine("NET_DVR_SET_CARD exception error: " + CHCNetSDK.NET_DVR_GetLastError());
                    msj = "NET_DVR_SET_CARD exception error: " + CHCNetSDK.NET_DVR_GetLastError();
                    break;
                }
                else
                {
                    //Console.WriteLine("unknown status error: " + CHCNetSDK.NET_DVR_GetLastError());
                    msj = "unknown status error: " + CHCNetSDK.NET_DVR_GetLastError();
                    break;
                }
            }
            CHCNetSDK.NET_DVR_StopRemoteConfig(m_lSetCardCfgHandle);
            m_lSetCardCfgHandle = -1;
            Marshal.FreeHGlobal(ptrStruData);
            Marshal.FreeHGlobal(ptrdwState);
            return msj;
        }

        public String BuscarPersonas(int m_UserID)
        {
            if (m_lGetCardCfgHandle != -1)
            {
                CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetCardCfgHandle);
                m_lGetCardCfgHandle = -1;
            }
            String msj = "";
            // Crear la condición para obtener todas las tarjetas
            CHCNetSDK.NET_DVR_CARD_COND struCond = new CHCNetSDK.NET_DVR_CARD_COND();
            struCond.Init();
            struCond.dwSize = (uint)Marshal.SizeOf(struCond);
            struCond.dwCardNum = 0xffffffff;  // 0 para obtener todas las tarjetas

            IntPtr ptrStruCond = Marshal.AllocHGlobal((int)struCond.dwSize);
            Marshal.StructureToPtr(struCond, ptrStruCond, false);

            // Iniciar la consulta remota
            m_lGetCardCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(
                m_UserID,
                CHCNetSDK.NET_DVR_GET_CARD,
                ptrStruCond,
                (int)struCond.dwSize,
                null,
                IntPtr.Zero
            );

            if (m_lGetCardCfgHandle < 0)
            {
                //Console.WriteLine("NET_DVR_GET_CARD error: " + CHCNetSDK.NET_DVR_GetLastError());
                msj = "NET_DVR_GET_CARD error: " + CHCNetSDK.NET_DVR_GetLastError();
                Marshal.FreeHGlobal(ptrStruCond);
                return msj;
            }

            CHCNetSDK.NET_DVR_CARD_RECORD struData = new CHCNetSDK.NET_DVR_CARD_RECORD();
            struData.Init();
            struData.dwSize = (uint)Marshal.SizeOf(struData);

            IntPtr ptrStruData = Marshal.AllocHGlobal((int)struData.dwSize);

            while (true)
            {
                int dwState = CHCNetSDK.NET_DVR_GetNextRemoteConfig(m_lGetCardCfgHandle, ptrStruData, struData.dwSize);

                if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_NEEDWAIT)
                {
                    Thread.Sleep(10);
                    continue;
                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_GET_NEXT_STATUS.NET_SDK_GET_NEXT_STATUS_SUCCESS)
                {
                    struData = (CHCNetSDK.NET_DVR_CARD_RECORD)Marshal.PtrToStructure(ptrStruData, typeof(CHCNetSDK.NET_DVR_CARD_RECORD));
                    string cardNo = System.Text.Encoding.Default.GetString(struData.byCardNo).TrimEnd('\0');
                    string name = System.Text.Encoding.Default.GetString(struData.byName).TrimEnd('\0');

                    //Console.WriteLine($"Tarjeta: {cardNo}, Nombre: {name}, empleado: {EmployedNo}");
                    msj += "{CardNo:" + cardNo + ",nombre:" + name.TrimEnd('\u0000', '\u0001', '\u0002') + "},";

                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_GET_NEXT_STATUS.NET_SDK_GET_NEXT_STATUS_FINISH)
                {
                    //Console.WriteLine("Se han recuperado todos los registros."); 
                    break;
                }
                else if (dwState == (int)CHCNetSDK.NET_SDK_GET_NEXT_STATUS.NET_SDK_GET_NEXT_STATUS_FAILED)
                {
                    //Console.WriteLine("Error al recuperar los registros: " + CHCNetSDK.NET_DVR_GetLastError());
                    msj = "Error al recuperar los registros: " + CHCNetSDK.NET_DVR_GetLastError();
                    break;
                }
                else
                {
                    //Console.WriteLine("Excepción durante la consulta: " + CHCNetSDK.NET_DVR_GetLastError());
                    msj = "Excepción durante la consulta: " + CHCNetSDK.NET_DVR_GetLastError();
                    break;
                }
            }

            CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetCardCfgHandle);
            m_lGetCardCfgHandle = -1;

            Marshal.FreeHGlobal(ptrStruCond);
            Marshal.FreeHGlobal(ptrStruData);

            return msj;

        }
       /* public void BuscarPersonasOrg(int m_UserID)
        {
            if (m_lGetCardCfgHandle != -1)
            {
                if (CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetCardCfgHandle))
                {
                    m_lGetCardCfgHandle = -1;
                }
            }
            CHCNetSDK.NET_DVR_CARD_COND struCond = new CHCNetSDK.NET_DVR_CARD_COND();
            struCond.Init();
            struCond.dwSize = (uint)Marshal.SizeOf(struCond);
            struCond.dwCardNum = 1;
            IntPtr ptrStruCond = Marshal.AllocHGlobal((int)struCond.dwSize);
            Marshal.StructureToPtr(struCond, ptrStruCond, false);

            CHCNetSDK.NET_DVR_CARD_RECORD struData = new CHCNetSDK.NET_DVR_CARD_RECORD();
            struData.Init();
            struData.dwSize = (uint)Marshal.SizeOf(struData);
            byte[] byTempCardNo = new byte[CHCNetSDK.ACS_CARD_NO_LEN];
            byTempCardNo = System.Text.Encoding.UTF8.GetBytes("1992");
            for (int i = 0; i < byTempCardNo.Length; i++)
            {
                struData.byCardNo[i] = byTempCardNo[i];
            }
            IntPtr ptrStruData = Marshal.AllocHGlobal((int)struData.dwSize);
            Marshal.StructureToPtr(struData, ptrStruData, false);

            CHCNetSDK.NET_DVR_CARD_SEND_DATA struSendData = new CHCNetSDK.NET_DVR_CARD_SEND_DATA();
            struSendData.Init();
            struSendData.dwSize = (uint)Marshal.SizeOf(struSendData);
            for (int i = 0; i < byTempCardNo.Length; i++)
            {
                struSendData.byCardNo[i] = byTempCardNo[i];
            }
            IntPtr ptrStruSendData = Marshal.AllocHGlobal((int)struSendData.dwSize);
            Marshal.StructureToPtr(struSendData, ptrStruSendData, false);
            m_lGetCardCfgHandle = CHCNetSDK.NET_DVR_StartRemoteConfig(m_UserID, CHCNetSDK.NET_DVR_GET_CARD, ptrStruCond, (int)struCond.dwSize, null, IntPtr.Zero);
            if (m_lGetCardCfgHandle < 0)
            {
                Console.WriteLine("NET_DVR_GET_CARD error: " + CHCNetSDK.NET_DVR_GetLastError());
                Marshal.FreeHGlobal(ptrStruCond);
                return;
            }
            else
            {
                int dwState = (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_SUCCESS;
                uint dwReturned = 0;
                while (true)
                {
                    dwState = CHCNetSDK.NET_DVR_SendWithRecvRemoteConfig(m_lGetCardCfgHandle, ptrStruSendData, struSendData.dwSize, ptrStruData, struData.dwSize, ref dwReturned);
                    struData = (CHCNetSDK.NET_DVR_CARD_RECORD)Marshal.PtrToStructure(ptrStruData, typeof(CHCNetSDK.NET_DVR_CARD_RECORD));
                    if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_NEEDWAIT)
                    {
                        Thread.Sleep(10);
                        continue;
                    }
                    else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_FAILED)
                    {
                        Console.WriteLine("NET_DVR_GET_CARD fail error: " + CHCNetSDK.NET_DVR_GetLastError());
                    }
                    else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_SUCCESS)
                    {
                        //textBoxCardNo.Text = System.Text.Encoding.Default.GetString(struData.byCardNo);
                        //textBoxCardRightPlan.Text = struData.wCardRightPlan[0].ToString();
                        //textBoxEmployeeNo.Text = struData.dwEmployeeNo.ToString();
                        //textBoxName.Text = System.Text.Encoding.Default.GetString(struData.byName);
                        Console.WriteLine("usuraio:" + System.Text.Encoding.Default.GetString(struData.byName));

                        Console.WriteLine("NET_DVR_GET_CARD success");
                    }
                    else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_FINISH)
                    {
                        Console.WriteLine("NET_DVR_GET_CARD finish");
                        break;
                    }
                    else if (dwState == (int)CHCNetSDK.NET_SDK_SENDWITHRECV_STATUS.NET_SDK_CONFIG_STATUS_EXCEPTION)
                    {
                        Console.WriteLine("NET_DVR_GET_CARD exception error: " + CHCNetSDK.NET_DVR_GetLastError());
                        break;
                    }
                    else
                    {
                        Console.WriteLine("unknown status error: " + CHCNetSDK.NET_DVR_GetLastError());
                        break;
                    }
                }
            }
            CHCNetSDK.NET_DVR_StopRemoteConfig(m_lGetCardCfgHandle);
            m_lGetCardCfgHandle = -1;
            Marshal.FreeHGlobal(ptrStruSendData);
            Marshal.FreeHGlobal(ptrStruData);
            return;
        }
       */
    }
}