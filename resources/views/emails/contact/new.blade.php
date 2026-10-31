<x-mail::message>
# {{ __('store.contact_email_title') }}

**{{ __('store.contact_sender') }}:** {{ $contact['name'] }}  
**{{ __('store.contact_sender_email') }}:** {{ $contact['email'] }}  
**{{ __('store.contact_topic') }}:** {{ $contact['subject'] }}

## {{ __('store.contact_message_label') }}

{!! nl2br(e($contact['message'])) !!}

{{ config('app.name') }}
</x-mail::message>
