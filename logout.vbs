On error resume next

'audit des déconnexions active directory
'on émet un POST HTTP, sans passer par le proxy, sur le serveur de log des connexions
Dim  o, n, data, secopt, strComputer, objWMIService, colItems, objItem
Dim manufacturer, model, systemType, mac, ip, description
Set o = CreateObject("WinHttp.WinHttpRequest.5.1")
strComputer = "localhost"
Set objWMIService = GetObject("winmgmts:{impersonationLevel=impersonate}!\\" & strComputer & "\root\cimv2")

' collecte marque, modele, arch
Set colItems = objWMIService.ExecQuery("Select * from Win32_ComputerSystem")
For Each objItem in colItems
    manufacturer = trim(objItem.Manufacturer)
    model = trim(objItem.Model)
    systemType = trim(objItem.SystemType)
next

' collecte mac address
Set colItems = objWMIService.ExecQuery("Select * from Win32_NetworkAdapterConfiguration")
For Each objItem in colItems
    if Not IsNull(objItem.IPAddress)  then
        mac = objItem.MACAddress
        ip = objItem.IPAddress(0)
        description = objItem.Description
    end if
next

' emission requête
Set n = CreateObject("wscript.network")
o.setproxy 1
o.Option(4) = 13056 'pour forcer à ignorer toutes les erreurs de certificats
o.open "POST", "https://winlog.iut.local/", False
o.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
data = "code=HK9!-uu&action=D&username=" + LCase(n.Username) + "&computer=" +n.ComputerName
data = data + "&manufacturer=" + manufacturer + "&model=" + model + "&systemType=" + systemType
data = data + "&mac=" + mac + "&ip=" + ip + "&descr=" + description
o.send data

