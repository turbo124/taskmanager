hp

namespace App;

use App\Models\Lead;
use BeyondCode\Mailbox\InboundEmail;

class LeadMailHandler {
    public function __invoke(InboundEmail $email) {
        ReceivedMail::create([
            'first_name'    => $email->from(),
            'name'          => $email->subject(),
            'description'   => $email->text(),
        ]);
    }
}
