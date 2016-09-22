Dim  o, n, data
Set o = CreateObject("WinHttp.WinHttpRequest.5.1")
Set n = CreateObject("wscript.network")
o.setproxy 1
o.open "POST", "http://winlog.dometud.iut-rodez.local/", False
o.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
data = "action=D&username="+n.Username+"&computer="+n.ComputerName
o.send data

