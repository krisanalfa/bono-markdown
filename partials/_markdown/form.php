<!-- START: Move this to your header layout to avoid multiple stylesheet when render the form more than one -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css">
<link rel="stylesheet" href="//demo.stanleyhlng.com/prettify-js/assets/themes/bootstrap-light/prettify.css">
<style>
    .markdown-input-source {
        font-size: 12px;
    }
    .markdown-input-wrapper {
        border:1px solid #ddd;
        border-radius: 4px;
    }
    .markdown-input-wrapper .nav-tabs {
        background-color: #f7f7f7;
        padding: 10px 10px 0 10px;
    }
    .markdown-input-wrapper .tab-pane {
        padding: 20px 20px 5px 20px;
    }
    .markdown-input-wrapper .help-block {
        padding: 0 20px;
        text-align: right;
    }
</style>
<!-- END: Move this to your header layout to avoid multiple stylesheet when render the form more than one -->

<div class="markdown-input-wrapper">
    <ul id="markdown" class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#source" role="tab" data-toggle="tab" class="markdown-source">Source</a></li>
        <li><a href="#preview-<?php echo $id; ?>" role="tab" data-toggle="tab" class="markdown-preview-<?php echo $id; ?>">Preview</a></li>
    </ul>
    <div id="markdownContent" class="tab-content">
        <div class="tab-pane active" id="source">
            <textarea data-editor="markdown" class="form-control markdown-input-source" name="" id="md-input-<?php echo $id; ?>" cols="30" rows="20"><?php echo $markdown; ?></textarea>
        </div>
        <div class="tab-pane" id="preview-<?php echo $id; ?>">
            <!-- Preview goes here :) -->
        </div>
    </div>

    <span class="help-block">
        <small class="text-muted">Your input parsed in <a href="https://help.github.com/articles/github-flavored-markdown">Github Flavored Markdown</a></small>
    </span>
</div>

<!-- START: Move these scripts to your header layout to avoid multiple javascript when render the form more than one -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<!-- Ace Editor -->
<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/mode-markdown.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/snippets/markdown.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/marked/0.3.2/marked.min.js"></script>

<!-- Google Code Prettify -->
<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
<!-- END: Move these scripts to your header layout to avoid multiple javascript when render the form more than one -->

<!-- Main Script -->
<script>
    // Just let Marked JS work with Google Code Prettify
    marked.setOptions({
        highlight: function (code, lang) {
            // Fix for HTML code block
            if (lang === 'html') {
                code = $('<div />').text(code).html();
            }

            return prettyPrintOne(code, lang);
        },
        gfm: true,
        tables: true
    });

    // Turn textarea to Ace Editor
    var textarea = $("#md-input-<?php echo $id; ?>"),
        editDiv = $('<div>', {
            position: 'absolute',
            width: textarea.width(),
            height: textarea.height(),
            'class': textarea.attr('class')
        }).insertBefore(textarea),
        editor = window.editor = ace.edit(editDiv[0]);

    textarea.css('display', 'none');

    editor.renderer.setShowGutter(false);
    editor.getSession().setValue(textarea.val());
    editor.getSession().setMode('ace/mode/markdown');
    editor.setTheme('ace/theme/github');
    editor.focus();

    // Set Ace Editor to fill the container
    $('.markdown-input-source').css('width', 'auto');

    // Markdown previewer
    $('#markdown a').click(function (event) {
        event.preventDefault();

        if ($(this).hasClass('markdown-preview-'+"<?php echo $id; ?>")) {
            $('#preview-'+"<?php echo $id; ?>").hide();

            var html = marked(editor.getValue());

            if (! html) {
                html = '<p>Nothing to preview</p>';
            }

            $('#preview-'+"<?php echo $id; ?>").html(html);

            prettyPrint();

            // Optional to give some styling
            $('#preview-'+"<?php echo $id; ?>"+' table').addClass('table table-striped table-bordered table-hover table-condensed');

            // Make our table become responsive
            if (! $('#preview-'+"<?php echo $id; ?>"+' table').closest('div').hasClass('table-responsive')) {
                $('#preview-'+"<?php echo $id; ?>"+' table').wrap('<div class="table-responsive"></div>');
            }

            $('#preview-'+"<?php echo $id; ?>").show();
        } else {
            $('#preview-'+"<?php echo $id; ?>").hide().html('');
        }

        $(this).tab('show');
    });
</script>
