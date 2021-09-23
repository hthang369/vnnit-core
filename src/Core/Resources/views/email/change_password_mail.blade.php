<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        .button {
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            color: #FFF;
            display: inline-block;
            text-decoration: none;
            -webkit-text-size-adjust: none;
        }
        .button-green {
            background-color: #2ab27b;
            border-top: 10px solid #2ab27b;
            border-right: 18px solid #2ab27b;
            border-bottom: 10px solid #2ab27b;
            border-left: 18px solid #2ab27b;
        }
        .header {
            padding: 25px 0;
            text-align: center;
        }
        .header a {
            color: #bbbfc3;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
            text-shadow: 0 1px 0 white;
        }
        .body {
            background-color: #FFFFFF;
            border-bottom: 1px solid #EDEFF2;
            border-top: 1px solid #EDEFF2;
            margin: 0;
            padding: 0;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }
        .inner-body {
            background-color: #FFFFFF;
            margin: 0 auto;
            padding: 0;
            width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 570px;
        }
        .content-cell {
            padding: 35px;
        }
        h1 {
            color: #2F3133;
            font-size: 19px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }
        p {
            color: #74787E;
            font-size: 16px;
            line-height: 1.5em;
            margin-top: 0;
            text-align: left;
        }
        img {
            max-width: 100%;
        }
    </style>
</head>
<body>


<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="header">
                        <a href="">
                            <img src="{{$pathToImage}}" alt="Images">
                        </a>
                    </td>
                </tr>
            <!-- Email Body -->
                <tr>
                    <h1 style="margin-top: 16px; text-align: center">{{$main['notification']}}</h1>
                    <p style="font-size: 14px">
                        {!! $main['note'] !!}
                    </p>
                </tr>
                <tr style="background-color: #F5F8FA">
                    <td align="center" style="padding: 40px 10px;color:black;font-size: 14px">
                        © {!! $footer !!}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

