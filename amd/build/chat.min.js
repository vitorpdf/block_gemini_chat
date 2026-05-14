
define(['jquery', 'core/str'], function($, Str) {

    'use strict';

    /**
     * Escape HTML special characters to prevent XSS.
     * @param {string} text
     * @returns {string}
     */
    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /**
     * Convert basic Markdown-like formatting to HTML.
     * Handles: **bold**, *italic*, `code`, and line breaks.
     * @param {string} text
     * @returns {string}
     */
    function formatMarkdown(text) {
        // Escape first, then apply formatting.
        var escaped = escapeHtml(text);

        // Code blocks (```...```) – must come before inline code.
        escaped = escaped.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');

        // Inline code.
        escaped = escaped.replace(/`([^`]+)`/g, '<code>$1</code>');

        // Bold.
        escaped = escaped.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

        // Italic.
        escaped = escaped.replace(/\*([^*]+)\*/g, '<em>$1</em>');

        // Line breaks (double newline → paragraph, single → <br>).
        escaped = escaped.replace(/\n\n/g, '</p><p>');
        escaped = escaped.replace(/\n/g, '<br>');
        escaped = '<p>' + escaped + '</p>';

        return escaped;
    }

    /**
     * Append a chat bubble to the history container.
     * @param {jQuery} $history
     * @param {string} role  'user' | 'ai'
     * @param {string} text
     * @param {string} roleLabel Human-readable label.
     */
    function appendBubble($history, role, text, roleLabel) {
        var $bubble = $('<div class="gemini-bubble gemini-bubble-' + role + '"></div>');
        var $label  = $('<span class="gemini-bubble-label"></span>').text(roleLabel);
        var $body   = $('<div class="gemini-bubble-body"></div>');

        if (role === 'ai') {
            $body.html(formatMarkdown(text));
        } else {
            $body.text(text);
        }

        $bubble.append($label).append($body);
        $history.append($bubble);

        // Scroll to the latest message.
        $history.scrollTop($history[0].scrollHeight);
    }

    /**
     * Initialise the chat block.
     * @param {Object} params
     * @param {number} params.blockid
     * @param {string} params.sesskey
     * @param {string} params.ajax_url
     */
    function init(params) {
        var blockId  = params.blockid;
        var ajaxUrl  = params.ajax_url;

        var $block   = $('#block-gemini-chat-'   + blockId);
        var $history = $('#gemini-chat-history-' + blockId);
        var $textarea= $('#gemini-question-'     + blockId);
        var $btn     = $('#gemini-send-'         + blockId);
        var $error   = $('#gemini-error-'        + blockId);
        var $spinner = $btn.find('.gemini-btn-spinner');
        var $btnText = $btn.find('.gemini-btn-text');

        if (!$block.length) {
            return;
        }

        // Load role strings asynchronously.
        var strings = {you: 'You', ai: 'Gemini AI'};
        Str.get_strings([
            {key: 'you', component: 'block_gemini_chat'},
            {key: 'ai',  component: 'block_gemini_chat'},
        ]).then(function(s) {
            strings.you = s[0];
            strings.ai  = s[1];
        });

        /**
         * Send a question to the server.
         */
        function sendQuestion() {
            var question = $textarea.val().trim();
            if (!question) {
                return;
            }

            // Hide previous errors.
            $error.addClass('d-none').text('');

            // Show user bubble.
            appendBubble($history, 'user', question, strings.you);

            // Show a "thinking" bubble.
            var $thinking = $('<div class="gemini-bubble gemini-bubble-ai gemini-thinking">' +
                '<span class="gemini-bubble-label">' + strings.ai + '</span>' +
                '<div class="gemini-bubble-body"><span class="gemini-dots">' +
                '<span></span><span></span><span></span></span></div></div>');
            $history.append($thinking);
            $history.scrollTop($history[0].scrollHeight);

            // Clear textarea and disable controls.
            $textarea.val('').prop('disabled', true);
            $btn.prop('disabled', true);
            $spinner.removeClass('d-none');
            $btnText.addClass('d-none');

            // POST to the AJAX endpoint.
            // Send as application/x-www-form-urlencoded so Moodle's
            // required_param() / confirm_sesskey() can read the fields.
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    question: question,
                    sesskey:  params.sesskey,
                },
                dataType: 'json',
            })
            .done(function(data) {
                $thinking.remove();

                if (data.error) {
                    $error.removeClass('d-none').text(data.error);
                } else {
                    appendBubble($history, 'ai', data.answer, strings.ai);
                }
            })
            .fail(function(xhr) {
                $thinking.remove();
                var msg = 'An unexpected error occurred.';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.error) { msg = resp.error; }
                } catch (e) { /* ignore */ }
                $error.removeClass('d-none').text(msg);
            })
            .always(function() {
                $textarea.prop('disabled', false).focus();
                $btn.prop('disabled', false);
                $spinner.addClass('d-none');
                $btnText.removeClass('d-none');
            });
        }

        // Click handler.
        $btn.on('click', sendQuestion);

        // Allow Ctrl+Enter or Shift+Enter to send.
        $textarea.on('keydown', function(e) {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                sendQuestion();
            }
        });
    }

    return {init: init};
});
