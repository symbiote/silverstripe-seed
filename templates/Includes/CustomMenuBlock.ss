<div class='custom-menu-block'>
	<% if MenuItems %>
		<% loop	MenuItems %>
			<div class='menu-item'>
				<% if LinkURL %>
					<h3><a href='{$LinkURL}' {$TargetAttr}>{$Title}</a></h3>
				<% else %>
					<h3>{$Title}</h3>
				<% end_if %>
				<% if Children %>
					<ul class='side-navigation'>
						<% loop Children %>
							<li><a href='{$LinkURL}'>{$Title}</a></li>
						<% end_loop %>
					</ul>
				<% end_if %>
			</div>
		<% end_loop %>
	<% end_if %>
</div>
