<div>
	<h1>{$Title}</strong></h1>
	<div><em>{$Date.Nice}</em></div>
	<br>
	{$Content}
	<% if Images %>
		<p>
			<% loop Images %>
				<span><a href='$Link'>{$CroppedImage(100, 100)}</a></span>
			<% end_loop %>
		</p>
	<% end_if %>
	<% if Attachments %>
		<em><strong>Attachments:</strong></em>
			<% loop Attachments %>
				<div><a href='{$Link}'>{$Title}</a></div>
			<% end_loop %>
	<% end_if %>
</div>