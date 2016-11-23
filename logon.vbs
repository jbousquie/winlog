Dim  o, n, data, secopt
Set o = CreateObject("WinHttp.WinHttpRequest.5.1")
Set n = CreateObject("wscript.network")
o.setproxy 1
o.Option(4) = 13056 'pour forcer à ignorer toutes les erreurs de certificats
o.open "POST", "https://winlog.iut.local/", False
o.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"
data = "action=C&username="+n.Username+"&computer="+n.ComputerName
o.send data
