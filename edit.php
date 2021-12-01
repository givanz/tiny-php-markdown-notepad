<?php $file = 'README.md';if (isset($_POST['content'])) file_put_contents($file, $_POST['content']);?>
<body>
<div id="container">
	<div id="edit">
		<form method="post">
			<textarea id="textarea" name="content" style="width:50vw"><?php echo file_get_contents($file);?></textarea>
			<button id="save">Save 📝</button>
		</form>
	</div>
	<div id="content">
		<div id="html"><?php echo file_get_contents($file);?></div>
	</div>
</div>
<script>
function parseMarkdown(markdownText) {
	let code = [];
	let codeIdx = 0;
	const htmlText = markdownText
		.replace(/!\[(.*?)\]\((.*?)\)/gm, "<img alt='$1' src='$2' />")
		.replace(/\[(.*?)\]\((.*?)\)/gm, "<a href='$2' target='blank'>$1</a>")//markdown links
		.replace(/^### (.*?$)/gm, "<h3>$1</h3>")
		.replace(/^## (.*?$)/gm, '<h2>$1</h2>')
		.replace(/^# (.*?$)/gm, '<h1>$1</h1>')
		.replace(/^\* (.*?$)/gm, '<li>$1</li>')
		.replace(/^\> (.*?$)/gm, '<blockquote>$1</blockquote>')
		.replace(/```(\w+\n)?(.+?)```/gsm,  function (x,y,z) {code.push(z); return '<pre><code>#__code' + codeIdx++  + '__#</code></pre>'; })
		.replace(/`([^`]+)`/gm, '<code>$1</code>')
		.replace(/\*\*([^<>]+?)\*\*/gm, '<b>$1</b>')
		.replace(/\*([^<>]+?)\*/gm, '<i>$1</i>')
		.replace(/\n(.+)$/gm, '<p>$1</p>')//paragraphs
		.replace(/\n$/gm, '<br />')
		.replace(/(\b(?<!['"])(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim, '<a href="$1" target="blank">$1</a>')//html links
		.replace(/#__code(\d+)__#/gsm,  function (x,y) { return code[y].replaceAll('>', '&gt;').replaceAll('<', '&lt;'); })

	return htmlText.trim();
}	

let html = document.getElementById("html");
let textarea = document.getElementById("textarea");
let timeout;

textarea.addEventListener("input", function(){
	clearTimeout(timeout);
	setTimeout(function() {
		html.innerHTML = parseMarkdown(textarea.value);
	}, 1000);
});

html.innerHTML = parseMarkdown(textarea.value);
</script>
<style>
	body {
		font-size:16px;
		line-height:24px;
		margin:0;
		padding:0;
	}
	
	img {
		max-width:100%;
	}
		
	#container, #edit, #content {
		height:100%;
		display:flex;
	}
	
	#textarea {
		height:100%;
		width:50%;
		min-width:10%;
		padding:2rem 2rem;
		border:2px solid #ccc;
	}
	
	#textarea, #html {
		padding:2rem 2rem;
		overflow-x:auto;
	}
	
	#edit {
		position:relative;
	}
		
	code {
		background: #eee;
		padding:0 0.5rem;
	}
	
	pre code {
		border-left: 2px solid #ddd;
		display: block;
		padding:0rem;
		padding-left: 1rem;
	}
	
	#save {
		position:absolute;
		right:0;
		top:0;
		box-shadow: 1px 1px 2px 1px rgba(0, 0, 0, 0.07), -1px 1px 2px 0px rgba(255, 255, 255, 0.15) inset;
		padding: 0.3rem 2rem;
		background-color: rgba(13, 110, 253, 0.8);
		border:none;
		cursor:pointer;
		color:#fff;
	}
	
	#save:hover {
		background-color: rgba(13, 110, 253, 1);
	}
</style>
</body>
