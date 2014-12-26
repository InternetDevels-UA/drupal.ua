<table style="width:650px;color: #2f383d;margin: 30px auto 0 auto;" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php print $message['headerlogo']; ?>" height="80px" width="650px"></td>
  </tr>
  <tr><td colspan="2" height="10px">&nbsp;</td></tr>
  <tr style="height: 110px;">
    <td colspan="2"  align="center" valign="top" style="font-family: Helvetica,Arial,sans-serif;font-weight: normal;line-height: 1.5em;font-size: 17px;color:#6e7477"><?php print $message['headertext']; ?></td>
  </tr>
  <?php foreach ($message['events'] as $delta => $item) : ?>
    <tr style="padding-bottom: 53px;height: 200px;" valign="top">
      <td style="padding: 0 20px 0 70px;">
        <img src="<?php print $item['logo']; ?>" style="width:112px;height:auto;"></td>
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 18px;"><?php print $item['title']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif; font-size: 13px;font-style: italic;line-height: 1.5em;"><?php print $item['date']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif;font-size: 13px;line-height: 1.5em;margin-bottom: 10px;"> <?php print $item['body']; ?>
        <a href="<?php print $item['readmore_link'];?>" style="color:#21c2f8;text-decoration: none;"><?php print $item['readmore_text'];?></a>
        </div>
        <a href="<?php print $item['register_link'];?>" style="color: white;background-color: #2f383d;text-decoration: none;padding: 5px 10px;"><?php print $item['register_text'];?></a>
        </td>
    </tr>
  <?php endforeach; ?>
  <tr style="background-color: #00C3FA;color:white;height:45px;font-family: Helvetica,Arial,sans-serif;font-size: 16px;" >
    <td colspan="2" align="center" valign="middle" ><?php print $message['footer_top']; ?></td>
  </tr>
  <tr style="background-color: #00C3FA;height:65px;">
    <td colspan="2" align="center" valign="top" >
      <a href="<?php print $message['facebook_link']; ?>" style="padding-right: 13px;"><img src="<?php print $message['facebook_img']; ?>" ></a>
      <a href="<?php print $message['vk_link']; ?>" style="padding-right: 13px;"><img src="<?php print $message['vk_img']; ?>" ></a>
      <a href="<?php print $message['twitter_link']; ?>"><img src="<?php print $message['twitter_img']; ?>" ></a>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" style="color: #8b8b8b;text-decoration: none;font-family: Helvetica,Arial,sans-serif;font-size: 13px;line-height: 1.5em;" >
      <div style="line-height: 2em;font-family: Helvetica,Arial,sans-serif;font-size: 20px;"><?php print $message['footer_supported_by']; ?></div>
      <?php print $message['footer_bottom']; ?>
    </td>
  </tr>
</table>
