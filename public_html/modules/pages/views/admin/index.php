<?= form_open('admin/pages/delete');?>
    
  <thead>
	<tr>
		<th class="first"><div></div></th>
		<th><a href="#">Page</a></th>
		<th><a href="#">Parent</a></th>
		<th><a href="#">Language</a></th>
		<th><a href="#">Updated</a></th>
		<th class="last"><span>Actions</span></th>
	</tr>
  </thead>

		foreach ($pages as $page): ?>
				<td><input type="checkbox" name="delete[<?=$page->id;?>]" <?=($page->slug == 'home') ? 'disabled="disabled"' : '' ?> /></td>
                    <td><?=isset($languages[$page->lang]) ? $languages[$page->lang] : 'Unknown';?></td>
		
