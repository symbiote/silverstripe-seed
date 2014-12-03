<div class='links-block'>
	<h4>{$Title}</h4>
	<% if Items %>
		<ul class='link-item'>
			<% loop Items %>
				<li>{$forTemplate}</li>
			<% end_loop %>
		</ul>
	<% end_if %>
</div>
