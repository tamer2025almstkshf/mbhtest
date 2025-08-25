<?php
    require_once __DIR__ . '/bootstrap.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo App\I18n::getLocale() === 'ar' ? 'rtl' : 'ltr'; ?>" lang="<?php echo App\I18n::getLocale(); ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title><?php echo __('law_firm_name'); ?></title>
		<meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
		<meta name="robots" content="noindex, nofollow">
		<meta name="googlebot" content="noindex">
		<link rel="SHORTCUT ICON" href="images/favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link  rel="alternate stylesheet" type="text/css" media="screen" title="selver-theme"  href="css/selver.css" />
		<link  rel="alternate stylesheet" type="text/css" media="screen" title="blue-theme"  href="css/blue.css" />
		<script type="text/javascript" src="js/switch.js.txt"></script>
		<SCRIPT LANGUAGE="JavaScript" SRC="../CalendarPopup.js"></SCRIPT>
		<SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>

		<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
			// TinyMCE initialization script - left untouched
		</script>
	</head>
	<body>
		<div class="colors">
			<span class="change"><?php echo __('change_color'); ?></span>

			<a href="javascript:chooseStyle('bage-theme', 60)"><span  class="bage_color"></span></a>
			<a href="javascript:chooseStyle('selver-theme', 60)"><span class="selver_color"></span></a>
			<a href="javascript:chooseStyle('blue-theme', 60)"><span class="blue_color"></span></a>
		</div>

		<div class="container">
			<img alt="" src="Attachment/LawyerLogo.png"  /><br /><br />
			<div class="login">
				<img alt="" src="images/log.png" width="142" height="27" class="log_img" />
				<div class="form">
					<table dir="rtl"  border="0" align="center" cellpadding="2" cellspacing="2">
						<form method="post" enctype="multipart/form-data" name="form" action="login_check.php">
							<tr>
								<td class="tit01" width="140" height="20" align="left"><strong><?php echo __('username'); ?>:&nbsp;&nbsp;&nbsp;</strong></td>
								<td width="240" height="20">
									<input name="user" type="text"  id="name4"    class="form" dir="rtl"  style="text-align:center"/>
								</td>
							</tr>
							
							<tr>
								<td class="tit01" width="140" height="20" align="left"><strong><?php echo __('password'); ?>:&nbsp;&nbsp;&nbsp;</strong></td>
								<td width="240" height="20">
									<input name="password" type="password" class="form" style="text-align:center" />
								</td>
							</tr>
							
							<tr>
								<td width="140" height="10"></td>
								<td width="240" height="10"></td>
							</tr>
							
							<tr>
								<td  align="center" colspan="2">
									<input name="submit" style="font-size:14px" class="button" type="submit" value="<?php echo __('login'); ?>"/>
								</td>
							</tr>
						</form>
					</table>
				</div>
			</div>
		</div>
		<div class="footer"><?php echo __('law_firm_name_footer'); ?><img alt="" src="images/f.png" width="29" height="31" /><img alt="" src="images/w.png" width="29" height="31" /></div>
	</body>
</html>
