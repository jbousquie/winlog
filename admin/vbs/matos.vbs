On error resume next

'récupération des infos "matériel"
'on émet un POST HTTP, sans passer par le proxy, sur le serveur de log des connexions
Dim  o, n, data, strComputer, objWMIService, colItems, objItem, objLogicalDisk
Dim manufacturer, model, systemType, ram, mac, procSpeed, diskSize, diskFreeSpace, ip, description, ipItem
Set o = CreateObject("WinHttp.WinHttpRequest.5.1")
strComputer = "localhost"
Set objWMIService = GetObject("winmgmts:{impersonationLevel=impersonate}!\\" & strComputer & "\root\cimv2")

' collecte marque, modele, arch
Set colItems = objWMIService.ExecQuery("Select * from Win32_ComputerSystem")
For Each objItem in colItems
    manufacturer = trim(objItem.Manufacturer)
    model = trim(objItem.Model)
    systemType = trim(objItem.SystemType)
    ram = objItem.TotalPhysicalMemory
next

' collecte vitesse processeur
Set colItems = objWMIService.ExecQuery("Select * from Win32_Processor")
For Each objItem in colItems
    procSpeed = objItem.MaxClockSpeed
Next

Set objLogicalDisk = objWMIService.Get("Win32_LogicalDisk.DeviceID='c:'")
diskSize = objLogicalDisk.Size
diskFreeSpace = objLogicalDisk.FreeSpace

' collecte mac address
Set colItems = objWMIService.ExecQuery("Select * from Win32_NetworkAdapterConfiguration")
mac = "["
ip = "["
description = "["
For Each objItem in colItems
    if Not IsNull(objItem.IPAddress)  then
        For Each ipItem in objItem.IPAddress
            mac = mac & chr(34) & objItem.MACAddress & chr(34) & ","
            ip = ip & chr(34) & ipItem & chr(34) & ","
            description = description & chr(34) & objItem.Description & chr(34) & ","
        next
    end if
next
mac = mac & "]"
ip = ip & "]"
description = description & "]"

' emission requête
Set n = CreateObject("wscript.network")
o.setproxy 1
o.Option(4) = 13056 'pour forcer à ignorer toutes les erreurs de certificats
o.open "POST", "##serverwinlog##", False
o.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
data = "code=#####&action=M&computer=" & n.ComputerName
data = data & "&manufacturer=" & manufacturer & "&model=" & model & "&systemType=" & systemType
data = data & "&ram=" & ram & "&procSpeed=" & procSpeed & "&diskSize=" & diskSize & "&diskFreeSpace=" & diskFreeSpace
data = data & "&mac=" & mac & "&ip=" & ip & "&descr=" & description
o.send data

