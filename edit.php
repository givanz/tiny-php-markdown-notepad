<?php $file = 'README.md';if (isset($_POST['content'])) file_put_contents($file, $_POST['content']);?>
<body>
<div id="container">
	<div id="edit">
		<form method="post">
			<textarea id="textarea" name="content" style="width:50vw"><?php echo file_get_contents($file);?></textarea>
			<div id="top-bar">
				<a id="color-theme" title="Color theme">☀️</a>
				<button id="save">Save 📝</button>
			</div>
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
		.replace(/^\s*### (.*?$)/gm, "<h3>$1</h3>")
		.replace(/^\s*## (.*?$)/gm, '<h2>$1</h2>')
		.replace(/^\s*# (.*?$)/gm, '<h1>$1</h1>')
		.replace(/^\s*\* (.*?$)/gm, '<li>$1</li>')
		.replace(/^\s*\> (.*?$)/gm, '<blockquote>$1</blockquote>')
		.replace(/```(\w+\n)?(.+?)```/gsm,  function (x,y,z) {code.push(z); return '<pre><code>#@@code' + codeIdx++  + '@@#</code></pre>'; })
		.replace(/`([^`]+)`/gm, '<code>$1</code>')
		.replace(/\*\*([^<>]+?)\*\*/gm, '<b>$1</b>')
		.replace(/__([^<>]+?)__/gm, '<strong>$1</strong>')
		.replace(/\*([^<>]+?)\*/gm, '<i>$1</i>')
		.replace(/^\s*-{3,}$/gm, '<hr />')
		.replace(/\n(.+)$/gm, '<p>$1</p>')//paragraphs
		.replace(/\n$/gm, '<br />')
		.replace(/(\b(?<!['"])(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim, '<a href="$1" target="blank">$1</a>')//html links
		.replace(/#@@code(\d+)@@#/gsm,  function (x,y) { return code[y].replaceAll('>', '&gt;').replaceAll('<', '&lt;'); })

	return htmlText.trim();
}	

let html = document.getElementById("html");
let textarea = document.getElementById("textarea");
let colortheme = document.getElementById("color-theme");
let timeout;

colortheme.addEventListener("click", function(e){
	if (this.innerHTML.codePointAt() == 9728) {
		this.innerHTML = '🌙';
		document.body.classList.add("dark");
		document.body.classList.remove("light");
	} else {
		this.innerHTML = '☀️';
		document.body.classList.add("light");
		document.body.classList.remove("dark");
	}
	
	e.preventDefault();
});

textarea.addEventListener("input", function(){
	clearTimeout(timeout);
	timeout = setTimeout(function() {
		html.innerHTML = parseMarkdown(textarea.value);
	}, 300);
});

html.innerHTML = parseMarkdown(textarea.value);
</script>
<style>
	body, body.light {
		font-size:1rem;
		line-height:1.5;
		margin:0;
		padding:0;
		--color:#24292f;
		--background:#fff;
		--border:#eee;
		color:var(--color);
		background:var(--background);
	}
	
	body.dark {
		--color:#fff;
		--background:#000;
		--border:#555;
	}
	
	@media (prefers-color-scheme: dark) {
	  body { 
		--color:#fff;
		--background:#000;
		--border:#555;
	  }
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
		border:2px solid (--border);
		color:var(--color);
		background:var(--background);
	}
	
	body.dark #textarea {
		color:#fff;
		background:#000;
	}
	
	#textarea, #html {
		padding:2rem 2rem;
		overflow-x:auto;
	}
	
	#edit {
		position:relative;
	}
		
	code {
		background: var(--border);
		color: var(--color);
		padding:0 0.5rem;
	}
	
	pre code {
		border-left: 2px solid var(--border);
		display: block;
		padding:0rem;
		padding-left: 1rem;
	}
	
	h1, h2 {
		border-bottom:1px solid var(--border);
		padding-bottom:0.5em;
		margin-bottom: 1rem;
	}
	
	hr{
		border:0;
		background: var(--border);
		height:0.3rem;
	}
	
	#top-bar {
		position:absolute;
		right:0;
		top:0;
	}
	#save {
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
	
	#color-theme {
		font-size:1.2rem;
		cursor:pointer;
	}
</style>
</body>
