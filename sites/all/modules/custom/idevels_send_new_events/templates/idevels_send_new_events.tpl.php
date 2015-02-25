<table style="width:650px;color: #2f383d;margin: 30px auto 0 auto;" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php print $message['headerlogo']; ?>" height="80px" width="650px"></td>
  </tr>
  <tr><td colspan="2" height="10px">&nbsp;</td></tr>
  <tr style="height: 94px;">
    <td colspan="2"  align="center" valign="top" style="font-family: Helvetica,Arial,sans-serif;font-weight: normal;line-height: 1.5em;font-size: 14px;color:#272727"><?php print $message['headertext']; ?></td>
  </tr>
  <?php if (!empty($message['questions'])): ?>
    <tr style="padding-bottom: 10px; height: 40px;" valign="top">
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 18px; text-transform: uppercase; border-bottom: 2px solid #21c2f8;width: 230px;"><?php print $message['questions_label'] ?></div>
      </td>
    </tr>
    <?php foreach ($message['questions'] as $delta => $item) : ?>
    <tr style="padding-bottom: 10px;height: 100px;" valign="top">
      <td colspan="2">
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;"><?php print $item['title']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif;font-size: 12px;line-height: 1.5em;margin-bottom: 10px;"> <?php print $item['body']; ?>
          <br /><a href="<?php print $item['readmore_link'];?>" style="color:#21c2f8;"><?php print $item['readmore_text'];?></a>
        </div>
        </td>
    </tr>
   <?php endforeach; ?>
    <tr style="padding-bottom: 10px;height: 70px;" valign="top">
      <td colspan="2">
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;width: 158px;margin: 0 auto;background: #272727;color: FFF;text-align: center;"><?php print $message['all_questions_label']; ?></div>
      </td>
    </tr>
  <?php endif; ?>
  <?php if (!empty($message['events'])): ?>
    <tr style="padding-bottom: 10px;height: 40px;" valign="top">
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 18px; text-transform: uppercase; border-bottom: 2px solid #21c2f8;width: 230px;"><?php print $message['events_label'] ?></div>
      </td>
    </tr>
  <?php foreach ($message['events'] as $delta => $item) : ?>
    <tr style="padding-bottom: 53px; height: 180px;" valign="top">
      <td style="padding: 0 20px 0 10px;">
        <img src="<?php print $item['logo']; ?>" style="width:112px;height:auto;"></td>
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;"><?php print $item['title']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif; font-size: 13px;line-height: 1.5em;color: #787878;"><?php print $item['date']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif;font-size: 12px;line-height: 1.5em;margin-bottom: 10px;"> <?php print $item['body']; ?>
          <br /><a href="<?php print $item['readmore_link'];?>" style="color:#21c2f8;"><?php print $item['readmore_text'];?></a>
        </div>
        </td>
    </tr>
  <?php endforeach; endif; ?>
  <?php if (!empty($message['new_events'])): ?>
    <tr style="padding-bottom: 10px;height: 40px;" valign="top">
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 18px; text-transform: uppercase;border-bottom: 2px solid #21C2F8;"><?php print $message['new_events_label'] ?></div>
      </td>
    </tr>
  <?php foreach ($message['new_events'] as $delta => $item) : ?>
    <tr style="padding-bottom: 10px; height: 80px;" valign="top">
      <td colspan="2">
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;display: inline-block;"><?php print $item['title']; ?>,</div>
        <div style="font-family: Helvetica,Arial,sans-serif; font-size: 13px;line-height: 1.5em;display: inline-block;color: #787878;"><?php print $item['date']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif;font-size: 12px;line-height: 1.5em;margin-bottom: 10px;"> <?php print $item['body']; ?>
          <br /><a href="<?php print $item['readmore_link'];?>" style="color:#21c2f8;"><?php print $item['readmore_text'];?></a>
        </div>
        </td>
    </tr>
  <?php endforeach; ?>
    <tr style="padding-bottom: 10px;height: 70px;" valign="top">
      <td colspan="2">
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;width: 158px;margin: 0 auto;background: #272727;color: FFF;text-align: center;"><?php print $message['all_new_events_label']; ?></div>
      </td>
    </tr>
  <?php endif; ?>
  <?php if (!empty($message['vacancies'])): ?>
    <tr style="padding-bottom: 10px; height: 40px;" valign="top">
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 18px; text-transform: uppercase; border-bottom: 2px solid #21c2f8;width: 230px;"><?php print $message['vacancies_label'] ?></div>
      </td>
    </tr>
  <?php foreach ($message['vacancies'] as $delta => $item) : ?>
    <tr style="padding-bottom: 10px; height: 100px;" valign="top">
      <td colspan="2">
        <div style="display:inline-block;width:385px;font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;"><?php print $item['title']; ?></div>
        <div style="width: 250px;text-align:right;font-family: Helvetica,Arial,sans-serif; font-size: 13px;line-height: 1.5em;color: #787878;font-weight: normal;display:inline-block;"><?php print $item['city']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif;font-size: 12px;line-height: 1.5em;margin-bottom: 10px;"> <?php print $item['body']; ?>
          <br /><a href="<?php print $item['readmore_link'];?>" style="color:#21c2f8;"><?php print $item['readmore_text'];?></a>
        </div>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr style="padding-bottom: 10px;height: 70px;" valign="top">
      <td colspan="2">
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;width: 158px;margin: 0 auto;background: #272727;color: FFF;text-align: center;"><?php print $message['all_vacancies_label']; ?></div>
      </td>
    </tr>
  <?php endif; ?>
  <?php if (!empty($message['posts'])): ?>
    <tr style="padding-bottom: 10px;height: 40px;" valign="top">
      <td>
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 18px; text-transform: uppercase; border-bottom: 2px solid #21c2f8;width: 230px;"><?php print $message['posts_label'] ?></div>
      </td>
    </tr>
  <?php foreach ($message['posts'] as $delta => $item) : ?>
    <tr style="padding-bottom: 10px;height: 100px;" valign="top">
      <td colspan="2">
        <div style="display:inline-block;width:385px;font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;"><?php print $item['title']; ?></div>
        <div style="width: 250px;display:inline-block;text-align:right;font-family: Helvetica,Arial,sans-serif; font-size: 13px;line-height: 1.5em;color: #787878;font-weight: normal;"><?php print $item['author']; ?></div>
        <div style="font-family: Helvetica,Arial,sans-serif;font-size: 12px;line-height: 1.5em;margin-bottom: 10px;"> <?php print $item['body']; ?>
          <br /><a href="<?php print $item['readmore_link'];?>" style="color:#21c2f8;"><?php print $item['readmore_text'];?></a>
        </div>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr style="padding-bottom: 10px;height: 70px;" valign="top">
      <td colspan="2">
        <div style="font-weight: bold;font-family: georgia, serif;line-height: 1.2em;font-size: 14px;width: 158px;margin: 0 auto;background: #272727;color: FFF;text-align: center;"><?php print $message['all_posts_label']; ?></div>
      </td>
    </tr>
  <?php endif; ?>
  <tr style="background-color: #00C3FA;color:white;height:45px;font-family: Helvetica,Arial,sans-serif;font-weight: bold;font-size: 18px;" >
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
