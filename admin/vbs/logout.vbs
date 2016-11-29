On error resume next

'audit des déconnexions active directory
'on émet un POST HTTP, sans passer par le proxy, sur le serveur de log des connexions
Dim  o, n, data
Set o = CreateObject("WinHttp.WinHttpRequest.5.1")

' emission requête
Set n = CreateObject("wscript.network")
o.setproxy 1
o.Option(4) = 13056 'pour forcer à ignorer toutes les erreurs de certificats
o.open "POST", "https://winlog.iut.local/", False
o.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
data = "code=#####&action=D&username=" & LCase(n.Username) & "&computer=" & n.ComputerName
o.send data

