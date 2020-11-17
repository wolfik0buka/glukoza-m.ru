<div id="notice" class="notice">
    <div class="panel top-15">
        <div class="panel-heading"
             data-toggle="collapse"
             data-target="#notice_body">
            <div class="row">
                {{--<div class="col-xs-2 text-center">--}}
                    {{--<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MTEuOTk5IDUxMS45OTkiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMS45OTkgNTExLjk5OTsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiMwMDk5N0M7IiBkPSJNNDcwLjk1NCw1MDAuNTMyTDM3Mi41OSwzMzIuNTNIMTM5LjQxNkw0MS4wNjgsNTAwLjUzMmMtMS40MDEsMi4zNzQtMS40MDEsNS4yNzgsMCw3LjY1MSAgYzEuMzcxLDIuMzU1LDMuOTE5LDMuODE2LDYuNjk0LDMuODE2aDQxNi40NjljMi43NywwLDUuMzIyLTEuNDYxLDYuNzIzLTMuODE2QzQ3Mi4zMjUsNTA1LjgxLDQ3Mi4zMjUsNTAyLjkwNiw0NzAuOTU0LDUwMC41MzJ6Ii8+CjxnIHN0eWxlPSJvcGFjaXR5OjAuMjsiPgoJPHBhdGggc3R5bGU9ImZpbGw6IzQxNDA0MjsiIGQ9Ik05Ny41MjIsNTA4LjE4NGMtMS40MDEtMi4zNzMtMS40MDEtNS4yNzgsMC03LjY1MWw5OC4zNDctMTY4LjAwMmgtNTYuNDUzTDQxLjA2OCw1MDAuNTMyICAgYy0xLjQwMSwyLjM3NC0xLjQwMSw1LjI3OCwwLDcuNjUxYzEuMzcxLDIuMzU1LDMuOTE5LDMuODE2LDYuNjk0LDMuODE2aDU2LjQ1NEMxMDEuNDQsNTEyLDk4Ljg5Miw1MTAuNTM5LDk3LjUyMiw1MDguMTg0eiIvPgo8L2c+CjxwYXRoIHN0eWxlPSJmaWxsOiMwMEI0OTQ7IiBkPSJNNDAzLjE3OSwzNDIuNTk4bC04MC4zNjctMTM3LjI1OUgxODkuMTc5bC04MC4zNjYsMTM3LjI1OWMtMS4zNzIsMi4zNzMtMS4zNzIsNS4yNzksMCw3LjY1MyAgYzEuNDA0LDIuMzUzLDMuOTUxLDMuODE3LDYuNzIzLDMuODE3aDI4MC45MjJjMi43NywwLDUuMzItMS40NjQsNi43MjQtMy44MTdDNDA0LjU0NiwzNDcuODc3LDQwNC41NDYsMzQ0Ljk3MSw0MDMuMTc5LDM0Mi41OTh6Ii8+CjxnIHN0eWxlPSJvcGFjaXR5OjAuMjsiPgoJPHBhdGggc3R5bGU9ImZpbGw6IzQxNDA0MjsiIGQ9Ik0xNjMuMjUxLDM1MC4yNTFjLTEuMzcyLTIuMzc0LTEuMzcyLTUuMjgsMC03LjY1M2w4MC4zNjYtMTM3LjI1OWgtNTQuNDM5bC04MC4zNjYsMTM3LjI1OSAgIGMtMS4zNzIsMi4zNzMtMS4zNzIsNS4yNzksMCw3LjY1M2MxLjQwNCwyLjM1MywzLjk1MSwzLjgxNyw2LjcyMywzLjgxN2g1NC40NEMxNjcuMjAzLDM1NC4wNjgsMTY0LjY1NSwzNTIuNjA0LDE2My4yNTEsMzUwLjI1MXoiLz4KPC9nPgo8cGF0aCBzdHlsZT0iZmlsbDojMDA5OTdDOyIgZD0iTTE2My40NjEsMjI0Ljc0M2MtMi44MDcsMC01LjM1NC0xLjQ2NS02LjcyNC0zLjgxOGMtMS40MDQtMi4zNzUtMS40MDQtNS4yOTMsMC03LjY1M0wyNDkuMjcsNTUuMTgxICBjMS40MDMtMi4zNzMsMy45NTQtMy44MTgsNi43MjUtMy44MThjMi43NzIsMCw1LjMyNCwxLjQ0NSw2LjcyNSwzLjgxOGw5Mi41MzUsMTU4LjA5MWMxLjM5OSwyLjM1OSwxLjM5OSw1LjI3OCwwLDcuNjUzICBjLTEuMzcyLDIuMzUzLTMuOTUzLDMuODE4LTYuNzI1LDMuODE4TDE2My40NjEsMjI0Ljc0M0wxNjMuNDYxLDIyNC43NDN6Ii8+CjxnIHN0eWxlPSJvcGFjaXR5OjAuMjsiPgoJPHBhdGggc3R5bGU9ImZpbGw6IzQxNDA0MjsiIGQ9Ik0yMDEuOTUsMjIwLjkyNWMtMS40MDQtMi4zNzUtMS40MDQtNS4yOTMsMC03LjY1M0wyNzguNiw4Mi4zMTVsLTE1Ljg4MS0yNy4xMzQgICBjLTEuNDAxLTIuMzczLTMuOTUyLTMuODE4LTYuNzI1LTMuODE4Yy0yLjc3LDAtNS4zMjIsMS40NDUtNi43MjUsMy44MThsLTkyLjUzMywxNTguMDkxYy0xLjQwNCwyLjM1OS0xLjQwNCw1LjI3OCwwLDcuNjUzICAgYzEuMzcsMi4zNTMsMy45MTcsMy44MTgsNi43MjQsMy44MThoNDUuMjEzQzIwNS44NjcsMjI0Ljc0MywyMDMuMzIsMjIzLjI3OCwyMDEuOTUsMjIwLjkyNXoiLz4KPC9nPgo8Zz4KCTxwYXRoIHN0eWxlPSJmaWxsOiNGMEQzNTU7IiBkPSJNMjgyLjg1NywxMDEuNTU4Yy0xLjAzMiwwLTIuMDU2LTAuMTctMy4wNDMtMC41MTFsLTI0LjA2Ny04LjI5OWwtMjQuMDc0LDguMjk5ICAgYy0wLjk5OSwwLjM0MS0yLjAyNSwwLjUxMS0zLjA1MywwLjUxMWMtMS45MzgsMC0zLjg1Ny0wLjYwNS01LjQ2Ny0xLjc2OGMtMi40NjItMS43ODItMy44OTItNC42NDktMy44MzItNy42NzZsMC40ODktMjUuMzQgICBsLTE1LjM3LTIwLjIwM2MtMS44MzctMi40MTctMi4zNzMtNS41NzEtMS40My04LjQ1YzAuOTQ5LTIuODg1LDMuMjM3LTUuMTIxLDYuMTQ0LTYuMDA4bDI0LjM4MS03LjM1NGwxNC41NzMtMjAuNzkxICAgQzI0OS44NTMsMS40ODQsMjUyLjY5NCwwLDI1NS43NDgsMGMzLjAzLDAsNS44ODgsMS40ODQsNy42MTksMy45NjdsMTQuNTgzLDIwLjc5MWwyNC4zNzMsNy4zNTQgICBjMi45MTQsMC44ODcsNS4yMDgsMy4xMjMsNi4xNDQsNi4wMDhjMC45NCwyLjg3OSwwLjQwNSw2LjAzMy0xLjQzMSw4LjQ1bC0xNS4zNTgsMjAuMjAzbDAuNDg3LDI1LjM0ICAgYzAuMDU2LDMuMDI2LTEuMzczLDUuODk0LTMuODM3LDcuNjc2QzI4Ni43MTgsMTAwLjk0NywyODQuNzkxLDEwMS41NTgsMjgyLjg1NywxMDEuNTU4eiIvPgoJPGNpcmNsZSBzdHlsZT0iZmlsbDojRjBEMzU1OyIgY3g9IjIyNi4zNTYiIGN5PSIxODkuMTY4IiByPSIxMi45MzUiLz4KPC9nPgo8Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMjg1LjY0MyIgY3k9IjE4OS4xNjgiIHI9IjEyLjkzNSIvPgo8Y2lyY2xlIHN0eWxlPSJmaWxsOiNGMEQzNTU7IiBjeD0iMTY1LjQ1OSIgY3k9IjMyMC42NjkiIHI9IjEyLjkzNSIvPgo8Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMjI1LjgyMyIgY3k9IjMyMC42NjkiIHI9IjEyLjkzNSIvPgo8Y2lyY2xlIHN0eWxlPSJmaWxsOiNGMEQzNTU7IiBjeD0iMjg2LjE3NiIgY3k9IjMyMC42NjkiIHI9IjEyLjkzNSIvPgo8Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMzQ2LjU0IiBjeT0iMzIwLjY2OSIgcj0iMTIuOTM1Ii8+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0YwRDM1NTsiIGN4PSIxMDUuMDk1IiBjeT0iNDc0LjgxMiIgcj0iMTIuOTM1Ii8+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGN4PSIxNjUuNDU5IiBjeT0iNDc0LjgxMiIgcj0iMTIuOTM1Ii8+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0YwRDM1NTsiIGN4PSIyMjUuODIzIiBjeT0iNDc0LjgxMiIgcj0iMTIuOTM1Ii8+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGN4PSIyODYuMTc2IiBjeT0iNDc0LjgxMiIgcj0iMTIuOTM1Ii8+CjxjaXJjbGUgc3R5bGU9ImZpbGw6I0YwRDM1NTsiIGN4PSIzNDYuNTQiIGN5PSI0NzQuODEyIiByPSIxMi45MzUiLz4KPGNpcmNsZSBzdHlsZT0iZmlsbDojRkZGRkZGOyIgY3g9IjQwNi45MDQiIGN5PSI0NzQuODEyIiByPSIxMi45MzUiLz4KPGcgc3R5bGU9Im9wYWNpdHk6MC4xOyI+Cgk8cGF0aCBzdHlsZT0iZmlsbDojNDE0MDQyOyIgZD0iTTI0MS41MDIsNjEuOTI1Yy0xMC4yMzgtMTAuMjM4LTE2LjcxOS0yMi4zNS0xOS4wMzMtMzMuODI4bC0xMy4zMTYsNC4wMTcgICBjLTIuOTA3LDAuODg3LTUuMTk1LDMuMTIzLTYuMTQ0LDYuMDA4Yy0wLjk0MiwyLjg3OS0wLjQwNiw2LjAzMywxLjQzLDguNDVsMTUuMzY5LDIwLjIwM2wtMC40ODksMjUuMzQgICBjLTAuMDYxLDMuMDI2LDEuMzcsNS44OTQsMy44MzIsNy42NzZjMS42MTEsMS4xNjMsMy41MywxLjc2OCw1LjQ2NywxLjc2OGMxLjAyOCwwLDIuMDU0LTAuMTcsMy4wNTMtMC41MTFsMjQuMDc0LTguMjk5ICAgbDI0LjA2Nyw4LjI5OWMwLjk4NiwwLjM0MSwyLjAxLDAuNTExLDMuMDQzLDAuNTExYzEuOTM0LDAsMy44NjEtMC42MTEsNS40NjktMS43NjhjMi40NjQtMS43ODIsMy44OTMtNC42NDksMy44MzctNy42NzYgICBsLTAuMjEtMTAuOTE2QzI3Ni42MDUsODQuMjY2LDI1Ni45MjYsNzcuMzQ4LDI0MS41MDIsNjEuOTI1eiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />--}}
                {{--</div>--}}
                <div class="col-xs-12 remove-padding-l" style="font-size: 26px;">
                    <center>Режим работы в праздничные дни</center>
                </div>
            </div>

        </div>
        <div id="notice_body" class="panel-body collapse">
            <p>
                <a href="/index.php?page=stat&alias=contacts" class="font-s15 font-w500">
                    Магазин г. Санкт-Петербург, Б. Сампсониевском пр., д. 62, оф. 202 (дверь между Дикси и SUBWAY
                    домофон №202)
                </a>
            </p>
            <ul>
                <li>29.04 - 01.05 и 06.05 - 09.05 - закрыт</li>
            </ul>
            <p class="top-15">
                <a href="/index.php?page=stat&alias=contacts" class="font-s15 font-w500">
                    Магазин г. Санкт-Петербург, Сикейроса, д. 10, к. 4, лит А, ТК "Бульвар" помещение 4/2
                </a>
            </p>
            <ul>
                <li>30.04 - 01.05 - закрыт</li>
                <li>07.05 и 09.05 - закрыт</li>
                <li>08.05 - с 11:00 до 17:00</li>
            </ul>
            <p class="top-15">
                <a href="/index.php?page=stat&alias=contacts" class="font-s15 font-w500">
                    Магазин г.Вологда ул. Благовещенская, д. 26
                </a>
            </p>
            <ul>
                <li>30.04 - 01.05 - закрыт</li>
                <li>07.05 - 09.05 - закрыт</li>
            </ul>
        </div>
    </div>


</div>