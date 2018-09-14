<!doctype html>
<html>
    <head>
        <title>Forwarder</title>
        <script>
            window.onload=function(){
                //if(navigator.userAgent.indexOf('Safari')!=-1&&navigator.userAgent.indexOf('Chrome')==-1){
                    var cookies=document.cookie;

                    if(top.location!=document.location){
                        if(!cookies){
                            href=document.location.href;
                            href=(href.indexOf('?')==-1)?href+'?':href+'&';
                            top.location.href =href+'reref='+encodeURIComponent(document.referrer);
                        }
                    } else {

                        ts=new Date().getTime();document.cookie='ts='+ts;
                        rerefidx=document.location.href.indexOf('reref=');
                        if(rerefidx!=-1){
                            href=decodeURIComponent(document.location.href.substr(rerefidx+6));
                            window.location.replace(href);
                        }
                    }
                }
            //}
        </script>
    </head>
    <body>
        <table style="border: 0; padding: 0; margin: 0;" width="100%" height="100%">

            <tr>
                <td align="center" valign="center">

                    <p style="font-size: 1.5em; font-family: \"Helvetica Neue Light\", \"HelveticaNeue-Light\", \"Helvetica Neue\", Calibri, Helvetica, Arial, sans-serif">
                        Please click to reconnect to <?=\Idno\Core\site()->config()->getTitle()?>.
                    </p>

                </td>
            </tr>

        </table>
    </body>
</html>