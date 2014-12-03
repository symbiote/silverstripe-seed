<% if Items %>
	<div class='child-page-list-block'>
		<h4>{$Title}</h4>
		<% loop Items %>
			<div class='child-item'>
				<% if PageThumbnail %>
					<div class='thumbnail'>
						<a href='{$Link}'>{$PageThumbnail}</a>
					</div>
				<% end_if %>
				<div class='page-information'>
					<h4><a href='{$Link}'>{$Title.XML}</a></h4>
					<a class='page-more' href='{$Link}'>Read more on {$Title.XML}</a>
				</div>
			</div>
		<% end_loop %>
	</div>
<% end_if %>
