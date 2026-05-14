<?php



class block_gemini_chat extends block_base {

    //inicializa o bloco 
    public function init() {
        $this->title = get_string('pluginname', 'block_gemini_chat');
    }

    public function has_config() {
        return true;
    }

    public function applicable_formats() {
        return ['all' => true];
    }

    public function get_content() {
        global $PAGE, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        
        $PAGE->requires->js_call_amd('block_gemini_chat/chat', 'init', [
            [
                'blockid'  => $this->instance->id,
                'sesskey'  => sesskey(),
                'ajax_url' => (new moodle_url('/blocks/gemini_chat/ajax.php'))->out(false),
            ]
        ]);

        // construindo o  HTML.
        $this->content->text = $this->render_block_html();

        return $this->content;
    }

    /**
     * tansformando em HTML.
     */
    private function render_block_html() {
        $blockid = $this->instance->id;

        $html  = '<div class="block-gemini-chat" id="block-gemini-chat-' . $blockid . '">';

        
        $html .= '<div class="gemini-chat-history" id="gemini-chat-history-' . $blockid . '" aria-live="polite" aria-label="' . get_string('chathistory', 'block_gemini_chat') . '">';
        $html .= '<div class="gemini-welcome-msg">';
        $html .= '<span class="gemini-icon">✨</span> ';
        $html .= get_string('welcomemsg', 'block_gemini_chat');
        $html .= '</div>';
        $html .= '</div>';

        // Input area.
        $html .= '<div class="gemini-input-area">';
        $html .= '<textarea ';
        $html .= '    id="gemini-question-' . $blockid . '" ';
        $html .= '    class="gemini-textarea" ';
        $html .= '    placeholder="' . get_string('questionplaceholder', 'block_gemini_chat') . '" ';
        $html .= '    rows="3" ';
        $html .= '    aria-label="' . get_string('questionlabel', 'block_gemini_chat') . '"';
        $html .= '></textarea>';

        $html .= '<button ';
        $html .= '    id="gemini-send-' . $blockid . '" ';
        $html .= '    class="btn btn-primary gemini-send-btn" ';
        $html .= '    type="button" ';
        $html .= '    aria-label="' . get_string('send', 'block_gemini_chat') . '">';
        $html .= '  <span class="gemini-btn-text">' . get_string('send', 'block_gemini_chat') . '</span>';
        $html .= '  <span class="gemini-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>';
        $html .= '</button>';

        $html .= '</div>'; // .gemini-input-area

        // Error container.
        $html .= '<div class="gemini-error alert alert-danger d-none" id="gemini-error-' . $blockid . '" role="alert"></div>';

        $html .= '</div>'; 

        return $html;
    }
}
