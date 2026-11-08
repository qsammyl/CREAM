{{-- Shown to visitors without JavaScript (the JS path uses the inline .form-msg). --}}
@if (session('enquiry_status') === 'sent')
    <div class="form-ok" style="margin-bottom:16px;padding:14px 16px;border-radius:10px;font-size:14px;line-height:1.5;background:#F0FAF4;border:1px solid #B6DFC7;color:#1A5C38">
        <strong>Message sent.</strong> A member of our team will be in touch within one business day.
    </div>
@elseif ($errors->has('enquiry'))
    <div class="form-err" style="margin-bottom:16px;padding:14px 16px;border-radius:10px;font-size:14px;line-height:1.5;background:#FEF2F2;border:1px solid #FECACA;color:#991B1B">
        {{ $errors->first('enquiry') }}
    </div>
@elseif ($errors->any())
    <div class="form-err" style="margin-bottom:16px;padding:14px 16px;border-radius:10px;font-size:14px;line-height:1.5;background:#FEF2F2;border:1px solid #FECACA;color:#991B1B">
        Please check the form and try again. {{ $errors->first() }}
    </div>
@endif
