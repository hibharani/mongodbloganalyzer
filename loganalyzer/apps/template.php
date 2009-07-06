<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>LogAnalyzer</title>
  <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
</head>
<body>
  <div id="head">
    <div id="logo">
      <h1>LogAnalyzer<sup style="font-size:10px">v0.1</sup></h1>
      <p>powerby Symfony 2.0</p>
    </div>
    <div id="options">
      <form action="/index.php">
        <table>
          <tr>
            <th><label for="filterby">Filterby</label></th>
            <td><input type="text" name="filterby" id="filterby" /></td>
          </tr>
          <tr>
            <th><label for="priority">Priority</label></th>
            <td>
              <select name="priority" id="priority">
                <?php foreach($priorities as $p_key => $p_value): ?>
                <option value="<?php echo $p_key; ?>" <?php if($sf_request->getParameter('priority') == $p_key) { echo 'selected="selected"'; } ?>><?php echo $p_value;?></option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr>
            <th><label for="collection">Collection</label></th>
            <td>
              <select name="collection" id="collection">
                <?php foreach($collections as $coll_key => $coll_value): ?>
                <option value="<?php echo $coll_key; ?>" <?php if($sf_request->getParameter('collection') == $coll_key) { echo 'selected="selected"'; } ?>><?php echo $coll_value;?></option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <input type="submit" name="submit" value="Filter"></input>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div style="clear:both;"></div>
  </div>
  <div id="main">
    <table class="tablesorter" cellspacing="0">
      <thead>
        <tr>
          <th><span>Message:</span></th>
          <th><span>Time:</span></th>
          <th><span>Priority:</span></th>
        </tr>
      </thead>
      <tbody>
        <?php $nb = 0; ?>
        <?php while($cursor->hasNext()): ?>
        <?php $log = $cursor->getNext(); $nb++?>
        <tr class="<?php echo $nb % 2 == 0 ? 'even' : 'odd'; ?>">
          <td><?php echo $log['message']; ?></td>
          <td><?php echo strftime('%b %d %H:%M:%S', $log['time']); ?></td>
          <td><?php echo $log['priority']; ?></td>
        </tr>
        <?php endwhile;?>
      <tbody>
    </table>
  </div>
  <div id="pager">
    <?php if($pageNumber > 1): ?>
      <a href="/index.php?page=<?php echo ($pageNumber - 1), '&', $filterParams; ?>">prev</a>
    <?php endif;?>
    <?php if($hasMore): ?>
      <a href="/index.php?page=<?php echo ($pageNumber + 1), '&', $filterParams; ?>">next</a>
    <?php endif; ?>
  </div>
</body>
</html>