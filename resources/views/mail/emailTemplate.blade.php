<html>

    <head>
        <title>Email Template</title>
        <link rel="important stylesheet" href="chrome://messagebody/skin/messageBody.css">
    </head>

    <body>
        @php
        @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
        @$value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
        @endphp
        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml'>
            <head>
                <meta name='viewport' content='width=device-width' />
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                <title>Email Template</title>
                <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
            </head>

            <body itemscope itemtype='http://schema.org/EmailMessage' style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;">

                <table class='body-wrap' style='background-color: #f6f6f6;display:table;margin:0 auto;padding-top: 40px;'>
                    <tr>

                        <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                            <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>

                                    <tr>
                                        <td style='padding: 25px;'>
                                            <table width='100%' cellpadding='0' cellspacing='0'> 
                                                <tr>
                                                    <td style='text-align: center; padding: 0 0 15px'>
                                                        @if($logo)
                                                        <img src='{{@$logo}}' width='250'>
                                                        @elseif($value)
                                                        <div class="login-logo" >
                                                            <a href="#"><h1>{{ $value }}</h1></a>
                                                        </div>
                                                        @else
                                                        <div class="login-logo" style="">
                                                            <a href="#"><img width="100%" src="{{ URL::asset('assets/images/logo.png') }}" ></a>
                                                        </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?=$mailData?>
                                                
                                                <table style='font-family: arial, sans-serif; width: 100%;'>
                                                    <tr>
                                                        <td style="vertical-align:top;padding:10px 0px 0px 0px">
                                                        Thanks,<br>
                                                            @if(@$value)
                                                                {{@$value}} Team!!
                                                            @else
                                                                SafaiDaar Team!!
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>      
                                              
                                            </table>
                                              
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>

                    </tr>
                </table>

            </body>
        </html>

    </body>

</html>
