<!doctype html>
@if(app()->getLocale()=='en')
<html lang="en" dir="ltr">
@else
<html lang="ar" dir="rtl">
@endif

@php
	$direction=app()->getLocale()=='en' ? 'left' :'right';
	$dir=app()->getLocale()=='en' ? 'ltr' :'rtl';;
@endphp

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>@lang('email.email_signup_verify_subtitle')</title>
</head>

<body width="100%" style="margin:0px;width:100%;">
	<table align="center" border="0" color width="100%" cellpadding="0" cellspacing="0" dir="{{$dir}}" style="text-align:center;border-width:0px;width:100%;font:17px Tahoma,Geneva,Verdana,sans-serif;color:#4b4b4b;">
		<tr>
			<td align="center" style="text-align:center;padding:16px;">
				<table align="center" border="0" width="500px" cellpadding="0" cellspacing="0" dir="{{$dir}}" style="text-align:center;border-width:0px;max-width:500px;width:100%;min-width:288px;">

					<tr>
						<td align="{{$direction}}" style="text-align:{{$direction}};padding:8px 0px;">
							<span style="font-size:20px;color:#12733a;"><b>@lang('email.email_signup_verify_title')</b></span>
						</td>
					</tr>
					<tr>
						<td align="{{$direction}}" style="text-align:{{$direction}};padding:8px 0px;">
							<span>@lang('email.email_signup_verify_subtitle')</span>
						</td>
					</tr>
					<tr>
						<td align="{{$direction}}" style="text-align:{{$direction}};padding:16px 0;">
							<span>@lang('email.email_signup_verify_description')</span>
						</td>
					</tr>
					<tr>
						<td align="center" bgcolor="#F5F5F5" style="text-align:center;padding:16px 0;">
							<span style="font-size:20px"><b>{{$otpCode}}</b></span>
						</td>
					</tr>
					<tr>
						<td align="{{$direction}}" style="text-align:{{$direction}};padding:16px 0;">
							<span>@lang('email.email_signup_verify_note')</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>
