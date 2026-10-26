<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /** Φόρμα επικοινωνίας: validation, honeypot, αποστολή email στον διαχειριστή. */
    public function send(Request $request): RedirectResponse
    {
        // Honeypot: τα bots συμπληρώνουν το κρυφό πεδίο
        if ($request->filled('website')) {
            return back()->with('ok', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:150'],
            'type' => ['nullable', 'string', 'max:60'],
            'date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:3000'],
        ], [
            'name.required' => 'Συμπλήρωσε το όνομά σου.',
            'phone.required' => 'Συμπλήρωσε ένα τηλέφωνο.',
            'email.email' => 'Το email δεν είναι έγκυρο.',
            'date.date' => 'Η ημερομηνία δεν είναι έγκυρη.',
        ]);

        $body = collect([
            'Όνομα' => $data['name'],
            'Τηλέφωνο' => $data['phone'],
            'Email' => $data['email'] ?? '-',
            'Είδος εκδήλωσης' => $data['type'] ?? '-',
            'Ημερομηνία' => $data['date'] ?? '-',
            'Μήνυμα' => $data['message'] ?? '-',
        ])->map(fn ($v, $k) => "$k: $v")->implode("\n");

        Mail::raw($body, function ($mail) use ($data) {
            $mail->to(config('mail.contact_to', config('mail.from.address')))
                ->subject('Νέο αίτημα από xdcinema.gr: '.$data['name']);
            if (! empty($data['email'])) {
                $mail->replyTo($data['email'], $data['name']);
            }
        });

        return redirect()->to(route('home').'#contact')->with('ok', true);
    }
}
