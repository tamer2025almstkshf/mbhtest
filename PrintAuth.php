<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
		<title></title>
		<style>
			.bold{
				font-weight:bold;
			}
			textarea{
				height:20px;
				resize:none;
				-webkit-transition:height .1s ease-in-out;
				-moz-transition:height .1s ease-in-out;
				-o-transition:height .1s ease-in-out;
				-ms-transition:height .1s ease-in-out;
				transition:height .1s ease-in-out;
				text-align:right;
				border:0px;
				font-weight:bold;
				font-size:18px;
				font-family:Arial, Helvetica, sans-serif;
				overflow:hidden;
			}
		</style>  
		<script>
			function autoResize(e) {
				var ele = e.target;
				var t = ele.scrollTop;
				ele.scrollTop = 0
				console.log(ele)
				if (t > 0) {
					ele.style.height = (ele.offsetHeight + t + t) + "px";
				}
			}
		</script>
	</head>

	<body leftmargin="0">
		<?php
			if(isset($_GET['id']) && $_GET !== ''){

				$id = $_GET['id'];
				$query = "SELECT * FROM client WHERE id = '$id'";
				$result = mysqli_query($conn, $query);

				$row = mysqli_fetch_array($result);
			}
		?>
		<div align="left"><img alt="" src="images/Print.png"  style="cursor:pointer" onclick="print()" /></div>
		<br />
		<div id="PrintMainDiv">
			<br /><br />
			<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center" style="font-weight:normal; font-size:16px;">
				<tr>
					<td align="center" dir="rtl">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="font-weight:bold" align="right" dir="rtl"> بتاريخ : <?php echo date('Y-m-d');?> </td>
							</tr>
						</table>	
					</td>
				</tr>

				<tr>
					<td align="center" dir="rtl" style="font-weight:normal;font-size:24px; padding-bottom:10px"><font style="font-weight:bolder; ">
					تـــوكــــيل عـــام بالــقـضــــايـــا</font></td>
				</tr>
			
				<tr style="padding-top:30px; font-size:18px; font-weight:normal">
					<td height="32" align="right" dir="rtl">
						<div  align="justify" dir="rtl">
						انا الموقــــع أدنــاه / 
						<textarea onkeydown="autoResize(event)" style="width:300px"><?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo $row['arname'];} ?></textarea> 
						- <textarea onkeydown="autoResize(event)" style="width:100px"><?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo $row['country'];}?></textarea>الجنسية  - احمل بطاقة هوية اماراتية رقم 
						<textarea onkeydown="autoResize(event)"><?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo $row['idno'];}?></textarea> 
						بصفتــي الشخـــصيه و بأي صفة كانت قد وكلت المحـــامي/ مـحمـد بـني هــاشــم وكـالـه عـامـــه مـطــلقه لتـمثيلي فـي جمـيع الـدعـاوى الـتـي ترفع مني أو علي وله الحق في الحضور كمدعي او مدعى عليه أمــــــام جميع المحاكم بــدولــة الإمــارات الـعربيه المـتحـدة سـواء أكـانـت اتـحاديـه او مـحـليه بـإخـتـلاف أنـواعـهــا إداريا ومـدنيـا وشـرعيـا وجـزائيـا ابـتـــداءا واستئنافـا وله الحق في توكيل الغير تـمييزا ونـقضـا سـواء أمـام محـكمــة الـتمييـــــز فـي دبـي أو المـحكمة الاتحـاديـه الـعليـا فـي ابـوظبـي وكـذلــك أمـام الـنيابـة الـعامـة والـشرطـة ودوائـر الـتحقيـق ولـجان الايـجـارات ودوائـر الأراضي والأملاك والبلديات وكـافـة البـنوك بالدولـة ولـهـم الحـق فـي اقــامــــة الدعـوى المتقـابـلــــة وفــــــي المتـابعــة والمرافعـــة حتى ختامهـا والـخصومـة بصورة عــــامــة ، كـمــــــا خـولـهتم الحـق فـي ممـارسـة جميـع التصرفـات والأعمـال والإجــــراءات التــــي تحفــــظ حقـــــــوقـي وأن يـقــــدم بـإسمـــــه وبـإمضـــــاءه المـذكــــــرات والـطـلبــات وعـروض الشــراء وعــروض المـنــاقصـــات والمـزايــدات للـمحـاكم والسلـطـات المـختصـة ، وتـوجيـه الإنـذارات والإخطـارات بــــواسطـة الـكــــاتـــب الـعـدل وكـذلـك فـي تـقـديـم الـبينـة والـتصـديـق عـليـهـا والإعتـــراض على بــينـة الـخصـم والطـعـن فـيهـــا بـــكافــة أوجـه الـطعن ، وتـقديـم الـشهـود ،ومـناقـشتـهـم واستــجـواب الــــــخصــوم وطـلـب المـعـــــاينـــه ودعــــوى إثـبـــات الـحالــــه وتـعييـــن الـخبـراء والمـحكميـن والاعـتراض عـــــلى تـعيينـهـــم و التوقيع على وثيقة التحكيم أو مشارطة التحكيم والـتصـديـق عـلى اقـرار التـحكيـــم والاعـتراض عـليـــه وطـــــــلــب فـسخــة وشـطــب الـدعـــوى والـــــتنـازل عـنهـــا وإسـقـاطـهـا وتـرك الـخصـومـة فـيهــا ، وتـوجيـه الـيميـن وردهــــا وقــبــــــولهــــا ورد الـقضاة واعـضــاء الـنيـــابـــة ومخـــاصمتهــــا والــتشكــي منهــــم ،والإعــــــلان والـتبليــــغ والمــطالبــة بالحــق الشخصــي وصـرف الـنظـر عنــــــه , كـــما خولتـــــه حــــق استعمـال كـــافـــة الحقــوق والصلاحيــات الممنـوحـة لـي شرعــا وقـانونــا لمتـابعـة الدعـــوى الى آخـر مراحلهــا امــام جميـــــــع مراحلهــا أمـــام جمـيــــع المحــاكم علـى اختــلاف درجـاتهــا ومبـاشـرة كـافـة طـرق الطعـن أو التظلـم في الأحكـــام والقــرارات والأوامـر بطــريق الإستئنــاف والنقـض والاعتـراض عــلى الحـكم الغيــابي واعتــراض الغـير والتمـاس أعــادة النظـــــر والمـحاكمــة ، ولـهم حــق تقديــم كــافــــة المــدفوع والطلبــات العــارضـة والمـستعجـــلـة والأوامــر علـى العـرائـــض ولـهـم حـق طلـب الـــعـرض والإيـــداع وشمــول الأحكــام والقـرارات بالـنفـــاذ المـعجــل وتـنفيـذهـا لصـالحـي وتـقديـم أشـكـالات الـتنفيـذ لـصالــــــحـي وتـقديـم أشـكـالات الـتنفيـذ وطـلب وضـع الحـجـز الإحـتيــاطي والتنـــفيـذي ورفعـــه وحبـــس المـــــدين وطلــب إخـــــلاء سبيـلــه ومـنعــه مـن الســفــر والـــتصــالح عـــــــلى كـــــل الديـــن أو جـزء منه و استلام الأمانات و المحجوزات من المحاكم و السلطات و الجهات المختصة و البنوك في الدولة و صرف الشيكات من البنوك المحسوب عليها تلك الشيكات و تسليم و استلام الجوازات السفر و أيه وثائق أخرى و توكيل الغير أو أنابته في بعض أو كل ما وكل به وكالة خاصة و لأجله وقعت. 
						</div>	
					</td>
				</tr>

				<tr>
					<td align="center" style="padding-top:40px">
						<table  width="40%" border="0" cellspacing="1" cellpadding="0" align="left" style="font-size:22px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">
							<tr>
								<td align="center" dir="rtl" class="bold"> الموكل </td>
							</tr>
							<tr>
								<td align="center" dir="rtl" class="bold">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>