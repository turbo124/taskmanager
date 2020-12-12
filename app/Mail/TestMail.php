class TestMail extends Mailable {
    public $sender;
    public $subject;
    public $body;

    public function __construct($sender, $subject, $body) {
        $this->sender = $sender;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build() {
        return $this
            ->from($this->sender)
            ->subject($this->subject)
            ->markdown('tests.emails.testmail');
    }
}
