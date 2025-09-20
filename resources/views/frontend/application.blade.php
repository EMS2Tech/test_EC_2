@extends('frontend.layouts.master')

@section('title', 'Application')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Personal Details</h4>
                    </div>
                </div>
            </div> <!-- container-fluid -->

            <!-- General Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Application Form</h5>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form class="needs-validation" novalidate method="POST"
                                        action="{{ route('application.store') }}" enctype="multipart/form-data">
                                        @csrf

                                        <!-- Title -->
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Title</legend>
                                            <div class="col-sm-10 d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMr"
                                                        value="Mr" {{ old('title', $application->title ?? '') === 'Mr' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleMr">Mr</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMrs"
                                                        value="Mrs" {{ old('title', $application->title ?? '') === 'Mrs' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleMrs">Mrs</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMiss"
                                                        value="Miss" {{ old('title', $application->title ?? '') === 'Miss' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleMiss">Miss</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleRev"
                                                        value="Rev" {{ old('title', $application->title ?? '') === 'Rev' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleRev">Rev</label>
                                                </div>
                                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                            </div>
                                        </fieldset>

                                        <!-- Full Name -->
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <x-text-input id="full_name" name="full_name" type="text"
                                                class="form-control text-uppercase" :value="old('full_name', $application->full_name ?? '')" required autocomplete="name"
                                                oninput="this.value = this.value.toUpperCase()" />
                                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                            <small class="form-text text-muted">Use Block Letters</small>
                                        </div>

                                        <!-- Name with Initials -->
                                        <div class="mb-3">
                                            <label for="name_with_initials" class="form-label">Name With Initials</label>
                                            <x-text-input id="name_with_initials" name="name_with_initials" type="text"
                                                class="form-control text-uppercase" :value="old('name_with_initials', $application->name_with_initials ?? '')" required
                                                oninput="this.value = this.value.toUpperCase()" />
                                            <x-input-error :messages="$errors->get('name_with_initials')" class="mt-2" />
                                            <small class="form-text text-muted">Use Block Letters</small>
                                        </div>


                                        <!-- Birth Day -->
                                        <div class="mb-3">
                                            <label for="birthday" class="form-label">Birth Day</label>
                                            <x-text-input id="birthday" name="birthday" type="date" class="form-control"
                                                :value="old('birthday', $application->birthday ?? '')" required />
                                            <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
                                        </div>

                                        <!-- Nationality -->
                                        <div class="mb-3">
                                            <label for="nationality" class="form-label">Nationality</label>
                                            <select class="form-select" id="nationality" name="nationality" required
                                                onchange="handleNationalityChange()">
                                                <option value="" disabled {{ !old('nationality', $application->nationality ?? '') ? 'selected' : '' }}>-- Select Nationality --</option>
                                                <option value="Sri Lanka" {{ old('nationality', $application->nationality ?? '') === 'Sri Lanka' ? 'selected' : '' }}>Sri Lankan</option>
                                                <option value="Other" {{ old('nationality', $application->nationality ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                                        </div>

                                        <!-- Sri Lankan Section -->
                                        <div id="sriLankanFields"
                                            style="display: {{ old('nationality', $application->nationality ?? 'Sri Lanka') === 'Sri Lanka' ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label for="nic_number" class="form-label">National ID Card Number</label>
                                                <x-text-input id="nic_number" name="nic_number" type="text"
                                                    class="form-control" :value="old('nic_number', $application->nic_number ?? '')" required />
                                                <x-input-error :messages="$errors->get('nic_number')" class="mt-2" />
                                                <small class="form-text text-muted">Old ID Format - 123456789V<br>New ID
                                                    Format - 123456789012</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nic_photo" class="form-label">Scan Copy of National ID
                                                    Card</label>
                                                <input class="form-control" type="file" id="nic_photo" name="nic_photo"
                                                    accept=".pdf, .png, .jpg, .jpeg" required />
                                                <x-input-error :messages="$errors->get('nic_photo')" class="mt-2" />
                                                <small class="form-text text-muted">Maximum Size 4MB - Accepted formats: PDF, PNG, JPG</small>
                                            </div>
                                        </div>

                                        <!-- Other Nationality Section -->
                                        <div id="otherNationalityFields"
                                            style="display: {{ old('nationality', $application->nationality ?? 'Sri Lanka') === 'Other' ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label for="other_nationality" class="form-label">Nationality</label>
                                                <x-text-input id="other_nationality" name="other_nationality" type="text"
                                                    class="form-control" :value="old('other_nationality', $application->other_nationality ?? '')"
                                                    placeholder="Enter Nationality" />
                                                <x-input-error :messages="$errors->get('other_nationality')" class="mt-2" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="passport_number" class="form-label">Passport Number</label>
                                                <x-text-input id="passport_number" name="passport_number" type="text"
                                                    class="form-control" :value="old('passport_number', $application->passport_number ?? '')" required />
                                                <x-input-error :messages="$errors->get('passport_number')" class="mt-2" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="passport_photo" class="form-label">Scan Copy Passport</label>
                                                <input class="form-control" type="file" id="passport_photo"
                                                    name="passport_photo" accept=".pdf, .png, .jpg, .jpeg" />
                                                <x-input-error :messages="$errors->get('passport_photo')" class="mt-2" />
                                                <small class="form-text text-muted">Maximum Size 4MB - Accepted formats: PDF, PNG, JPG</small>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Photograph -->
                                        <div class="mb-3">
                                            <div class="mt-2">
                                                <img src="{{ asset('frontend/assets/images/sample_photograph.png') }}"
                                                    alt="Sample Photograph" class="img-thumbnail"
                                                    style="max-width: 150px; cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#samplePhotographModal"><br>
                                                <small class="form-text text-muted">Sample photograph for reference. Click
                                                    to view full size.</small>

                                                <!-- Modal -->
                                                <div class="modal fade" id="samplePhotographModal" tabindex="-1"
                                                    aria-labelledby="samplePhotographModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="samplePhotographModalLabel">
                                                                    Sample Photograph</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img src="{{ asset('frontend/assets/images/sample_photograph.png') }}"
                                                                    alt="Sample Photograph" class="img-fluid">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><br>
                                            <label for="photograph" class="form-label">Photograph</label>
                                            <input class="form-control" type="file" id="photograph" name="photograph"
                                                accept="image/*" required />
                                            <x-input-error :messages="$errors->get('photograph')" class="mt-2" />
                                            <small class="form-text text-muted">Maximum Size 4MB - Accepted formats: PNG, JPG</small>
                                        </div>
                                        <hr>

        <!-- Country -->
<div class="col-md-12 mb-3">
    <label class="form-label">Country</label>
    <select class="form-select" id="country" name="country" required onchange="updateFields(); updatePhonePrefix();">
        <option value="">-- Select Country --</option>
        @php
            $countries = [
                ['name' => 'Afghanistan', 'code' => '93'],
['name' => 'Albania', 'code' => '355'],
['name' => 'Algeria', 'code' => '213'],
['name' => 'Andorra', 'code' => '376'],
['name' => 'Angola', 'code' => '244'],
['name' => 'Antigua and Barbuda', 'code' => '1268'],
['name' => 'Argentina', 'code' => '54'],
['name' => 'Armenia', 'code' => '374'],
['name' => 'Australia', 'code' => '61'],
['name' => 'Austria', 'code' => '43'],
['name' => 'Azerbaijan', 'code' => '994'],

['name' => 'Bahamas', 'code' => '1242'],
['name' => 'Bahrain', 'code' => '973'],
['name' => 'Bangladesh', 'code' => '880'],
['name' => 'Barbados', 'code' => '1246'],
['name' => 'Belarus', 'code' => '375'],
['name' => 'Belgium', 'code' => '32'],
['name' => 'Belize', 'code' => '501'],
['name' => 'Benin', 'code' => '229'],
['name' => 'Bhutan', 'code' => '975'],
['name' => 'Bolivia', 'code' => '591'],
['name' => 'Bosnia and Herzegovina', 'code' => '387'],
['name' => 'Botswana', 'code' => '267'],
['name' => 'Brazil', 'code' => '55'],
['name' => 'Brunei', 'code' => '673'],
['name' => 'Bulgaria', 'code' => '359'],
['name' => 'Burkina Faso', 'code' => '226'],
['name' => 'Burundi', 'code' => '257'],

['name' => 'Cabo Verde', 'code' => '238'],
['name' => 'Cambodia', 'code' => '855'],
['name' => 'Cameroon', 'code' => '237'],
['name' => 'Canada', 'code' => '1'],
['name' => 'Central African Republic', 'code' => '236'],
['name' => 'Chad', 'code' => '235'],
['name' => 'Chile', 'code' => '56'],
['name' => 'China', 'code' => '86'],
['name' => 'Colombia', 'code' => '57'],
['name' => 'Comoros', 'code' => '269'],
['name' => 'Congo (Congo-Brazzaville)', 'code' => '242'],
['name' => 'Costa Rica', 'code' => '506'],
['name' => 'Croatia', 'code' => '385'],
['name' => 'Cuba', 'code' => '53'],
['name' => 'Cyprus', 'code' => '357'],
['name' => 'Czechia (Czech Republic)', 'code' => '420'],

['name' => 'Democratic Republic of the Congo', 'code' => '243'],
['name' => 'Denmark', 'code' => '45'],
['name' => 'Djibouti', 'code' => '253'],
['name' => 'Dominica', 'code' => '1767'],
['name' => 'Dominican Republic', 'code' => '1809'],

['name' => 'Ecuador', 'code' => '593'],
['name' => 'Egypt', 'code' => '20'],
['name' => 'El Salvador', 'code' => '503'],
['name' => 'Equatorial Guinea', 'code' => '240'],
['name' => 'Eritrea', 'code' => '291'],
['name' => 'Estonia', 'code' => '372'],
['name' => 'Eswatini', 'code' => '268'],
['name' => 'Ethiopia', 'code' => '251'],

['name' => 'Fiji', 'code' => '679'],
['name' => 'Finland', 'code' => '358'],
['name' => 'France', 'code' => '33'],

['name' => 'Gabon', 'code' => '241'],
['name' => 'Gambia', 'code' => '220'],
['name' => 'Georgia', 'code' => '995'],
['name' => 'Germany', 'code' => '49'],
['name' => 'Ghana', 'code' => '233'],
['name' => 'Greece', 'code' => '30'],
['name' => 'Grenada', 'code' => '1473'],
['name' => 'Guatemala', 'code' => '502'],
['name' => 'Guinea', 'code' => '224'],
['name' => 'Guinea-Bissau', 'code' => '245'],
['name' => 'Guyana', 'code' => '592'],

['name' => 'Haiti', 'code' => '509'],
['name' => 'Honduras', 'code' => '504'],
['name' => 'Hungary', 'code' => '36'],

['name' => 'Iceland', 'code' => '354'],
['name' => 'India', 'code' => '91'],
['name' => 'Indonesia', 'code' => '62'],
['name' => 'Iran', 'code' => '98'],
['name' => 'Iraq', 'code' => '964'],
['name' => 'Ireland', 'code' => '353'],
['name' => 'Israel', 'code' => '972'],
['name' => 'Italy', 'code' => '39'],

['name' => 'Jamaica', 'code' => '1876'],
['name' => 'Japan', 'code' => '81'],
['name' => 'Jordan', 'code' => '962'],

['name' => 'Kazakhstan', 'code' => '7'],
['name' => 'Kenya', 'code' => '254'],
['name' => 'Kiribati', 'code' => '686'],
['name' => 'Kuwait', 'code' => '965'],
['name' => 'Kyrgyzstan', 'code' => '996'],

['name' => 'Laos', 'code' => '856'],
['name' => 'Latvia', 'code' => '371'],
['name' => 'Lebanon', 'code' => '961'],
['name' => 'Lesotho', 'code' => '266'],
['name' => 'Liberia', 'code' => '231'],
['name' => 'Libya', 'code' => '218'],
['name' => 'Liechtenstein', 'code' => '423'],
['name' => 'Lithuania', 'code' => '370'],
['name' => 'Luxembourg', 'code' => '352'],

['name' => 'Madagascar', 'code' => '261'],
['name' => 'Malawi', 'code' => '265'],
['name' => 'Malaysia', 'code' => '60'],
['name' => 'Maldives', 'code' => '960'],
['name' => 'Mali', 'code' => '223'],
['name' => 'Malta', 'code' => '356'],
['name' => 'Marshall Islands', 'code' => '692'],
['name' => 'Mauritania', 'code' => '222'],
['name' => 'Mauritius', 'code' => '230'],
['name' => 'Mexico', 'code' => '52'],
['name' => 'Micronesia', 'code' => '691'],
['name' => 'Moldova', 'code' => '373'],
['name' => 'Monaco', 'code' => '377'],
['name' => 'Mongolia', 'code' => '976'],
['name' => 'Montenegro', 'code' => '382'],
['name' => 'Morocco', 'code' => '212'],
['name' => 'Mozambique', 'code' => '258'],
['name' => 'Myanmar (Burma)', 'code' => '95'],

['name' => 'Namibia', 'code' => '264'],
['name' => 'Nauru', 'code' => '674'],
['name' => 'Nepal', 'code' => '977'],
['name' => 'Netherlands', 'code' => '31'],
['name' => 'New Zealand', 'code' => '64'],
['name' => 'Nicaragua', 'code' => '505'],
['name' => 'Niger', 'code' => '227'],
['name' => 'Nigeria', 'code' => '234'],
['name' => 'North Korea', 'code' => '850'],
['name' => 'North Macedonia', 'code' => '389'],
['name' => 'Norway', 'code' => '47'],

['name' => 'Oman', 'code' => '968'],

['name' => 'Pakistan', 'code' => '92'],
['name' => 'Palau', 'code' => '680'],
['name' => 'Palestine', 'code' => '970'],
['name' => 'Panama', 'code' => '507'],
['name' => 'Papua New Guinea', 'code' => '675'],
['name' => 'Paraguay', 'code' => '595'],
['name' => 'Peru', 'code' => '51'],
['name' => 'Philippines', 'code' => '63'],
['name' => 'Poland', 'code' => '48'],
['name' => 'Portugal', 'code' => '351'],

['name' => 'Qatar', 'code' => '974'],

['name' => 'Romania', 'code' => '40'],
['name' => 'Russia', 'code' => '7'],
['name' => 'Rwanda', 'code' => '250'],

['name' => 'Saint Kitts and Nevis', 'code' => '1869'],
['name' => 'Saint Lucia', 'code' => '1758'],
['name' => 'Saint Vincent and the Grenadines', 'code' => '1784'],
['name' => 'Samoa', 'code' => '685'],
['name' => 'San Marino', 'code' => '378'],
['name' => 'Sao Tome and Principe', 'code' => '239'],
['name' => 'Saudi Arabia', 'code' => '966'],
['name' => 'Senegal', 'code' => '221'],
['name' => 'Serbia', 'code' => '381'],
['name' => 'Seychelles', 'code' => '248'],
['name' => 'Sierra Leone', 'code' => '232'],
['name' => 'Singapore', 'code' => '65'],
['name' => 'Slovakia', 'code' => '421'],
['name' => 'Slovenia', 'code' => '386'],
['name' => 'Solomon Islands', 'code' => '677'],
['name' => 'Somalia', 'code' => '252'],
['name' => 'South Africa', 'code' => '27'],
['name' => 'South Korea', 'code' => '82'],
['name' => 'South Sudan', 'code' => '211'],
['name' => 'Spain', 'code' => '34'],
['name' => 'Sri Lanka', 'code' => '94'],
['name' => 'Sudan', 'code' => '249'],
['name' => 'Suriname', 'code' => '597'],
['name' => 'Sweden', 'code' => '46'],
['name' => 'Switzerland', 'code' => '41'],
['name' => 'Syria', 'code' => '963'],

['name' => 'Taiwan', 'code' => '886'],
['name' => 'Tajikistan', 'code' => '992'],
['name' => 'Tanzania', 'code' => '255'],
['name' => 'Thailand', 'code' => '66'],
['name' => 'Timor-Leste', 'code' => '670'],
['name' => 'Togo', 'code' => '228'],
['name' => 'Tonga', 'code' => '676'],
['name' => 'Trinidad and Tobago', 'code' => '1868'],
['name' => 'Tunisia', 'code' => '216'],
['name' => 'Turkey', 'code' => '90'],
['name' => 'Turkmenistan', 'code' => '993'],
['name' => 'Tuvalu', 'code' => '688'],

['name' => 'Uganda', 'code' => '256'],
['name' => 'Ukraine', 'code' => '380'],
['name' => 'United Arab Emirates', 'code' => '971'],
['name' => 'United Kingdom', 'code' => '44'],
['name' => 'United States', 'code' => '1'],
['name' => 'Uruguay', 'code' => '598'],
['name' => 'Uzbekistan', 'code' => '998'],

['name' => 'Vanuatu', 'code' => '678'],
['name' => 'Vatican City', 'code' => '379'],
['name' => 'Venezuela', 'code' => '58'],
['name' => 'Vietnam', 'code' => '84'],

['name' => 'Yemen', 'code' => '967'],

['name' => 'Zambia', 'code' => '260'],
['name' => 'Zimbabwe', 'code' => '263'],

                // add more countries as needed
            ];
        @endphp
        @foreach($countries as $country)
            <option value="{{ $country['name'] }}" data-code="+{{ $country['code'] }}"
                {{ old('country', $application->country ?? '') === $country['name'] ? 'selected' : '' }}>
                {{ $country['name'] }} (+{{ $country['code'] }})
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('country')" class="mt-2" />
</div>

<!-- Address Section -->
<div class="mb-3">
    <label class="form-label">Address</label>
    <div class="row g-3">
        <!-- House Number -->
        <div class="col-md-6">
            <x-text-input id="house_number" name="house_number" type="text" class="form-control"
                :value="old('house_number', $application->house_number ?? '')" placeholder="House Number (Optional)" />
            <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
        </div>

        <!-- Street Name -->
        <div class="col-md-6">
            <x-text-input id="street_name" name="street_name" type="text" class="form-control"
                :value="old('street_name', $application->street_name ?? '')" placeholder="Street Name" required />
            <x-input-error :messages="$errors->get('street_name')" class="mt-2" />
        </div>

        <!-- Apartment / Building -->
        <div class="col-md-12">
            <x-text-input id="apartment" name="apartment" type="text" class="form-control"
                :value="old('apartment', $application->apartment ?? '')" placeholder="Apartment / Building (Optional)" />
            <x-input-error :messages="$errors->get('apartment')" class="mt-2" />
        </div>

        <!-- District -->
        <div class="col-md-6 district-province-fields" style="display: {{ old('country', $application->country ?? '') === 'Sri Lanka' ? 'block' : 'none' }};">
            <select class="form-select" id="district" name="district" {{ old('country', $application->country ?? '') === 'Sri Lanka' ? 'required' : '' }}>
                <option value="">-- Select District --</option>
                @foreach(['Colombo','Gampaha','Kalutara','Kandy','Matale','Nuwara Eliya','Galle','Matara','Hambantota',
                          'Jaffna','Kilinochchi','Mannar','Vavuniya','Mullaitivu','Batticaloa','Ampara','Trincomalee',
                          'Kurunegala','Puttalam','Anuradhapura','Polonnaruwa','Badulla','Monaragala','Ratnapura','Kegalle'] as $district)
                    <option value="{{ $district }}" {{ old('district', $application->district ?? '') === $district ? 'selected' : '' }}>
                        {{ $district }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('district')" class="mt-2" />
        </div>

        <!-- Province -->
        <div class="col-md-6 district-province-fields" style="display: {{ old('country', $application->country ?? '') === 'Sri Lanka' ? 'block' : 'none' }};">
            <select class="form-select" id="province" name="province" {{ old('country', $application->country ?? '') === 'Sri Lanka' ? 'required' : '' }}>
                <option value="">-- Select Province --</option>
                @foreach(['Western','Central','Southern','Northern','Eastern','North Western','North Central','Uva','Sabaragamuwa'] as $province)
                    <option value="{{ $province }}" {{ old('province', $application->province ?? '') === $province ? 'selected' : '' }}>
                        {{ $province }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('province')" class="mt-2" />
        </div>
    </div>
</div>
<!-- End Address Section -->

<!-- Contact Phone -->
<div class="mb-3">
    <label class="form-label">Contact Phone</label>
    <div class="input-group has-validation">
        <span class="input-group-text phone-prefix">+94</span>
        <x-text-input class="form-control" type="tel" name="contact_number"
            placeholder="Enter number" :value="old('contact_number', $application->contact_number ?? '')"
            required pattern="^[0-9]{6,15}$" />
        <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
    </div>
</div>

<!-- WhatsApp Phone -->
<div class="mb-3">
    <label class="form-label">WhatsApp Phone</label>
    <div class="input-group has-validation">
        <span class="input-group-text phone-prefix">+94</span>
        <x-text-input class="form-control" type="tel" name="whatsapp_number"
            placeholder="Enter number" :value="old('whatsapp_number', $application->whatsapp_number ?? '')"
            pattern="^[0-9]{6,15}$" />
        <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-2" />
    </div>
</div>

<!-- Home Phone -->
<div class="mb-3">
    <label class="form-label">Home Phone (Optional)</label>
    <div class="input-group">
        <span class="input-group-text phone-prefix">+94</span>
        <x-text-input class="form-control" type="tel" name="home_number"
            placeholder="Enter number" :value="old('home_number', $application->home_number ?? '')"
            pattern="^[0-9]{6,15}$" />
        <x-input-error :messages="$errors->get('home_number')" class="mt-2" />
    </div>
</div>

<!-- Mentor Name -->
<div class="mb-3">
    <label for="mentor_name" class="form-label">Coordinator Name </label>
    <select class="form-select" id="mentor_name" name="mentor_name" required>
        <option value="" disabled {{ !old('mentor_name', $application->mentor_name ?? '') ? 'selected' : '' }}>-- Select Coordinator --</option>
        @foreach([
            'Wijeneka Mudalige Harshani Ruwanthika',
            'Nethmi Nisansala',
            'Udari Madhuwanthi',
            'Lasika Supun Nadi Hewa Vitharana',
            'Sandeepa Wathurathanthri',
            'M Chathurika Harshani De Costa',
            'Saradha Samaraweera',
            'Dissanayaka Mudiyanselage Darshika Lanka Sanjeewani',
            'Sethun Pullige Prabhath Tharindu',
            'Erosha Shiromi Vithanage',
            'K K Sameera Tharanga',
            'Pimmachcharige Sulana Dulmika',
            'G A Tharindu Uthpala Rajathilaka',
            'A Nilaksha Harshani Silva'
        ] as $mentor)
            <option value="{{ $mentor }}" {{ old('mentor_name', $application->mentor_name ?? '') === $mentor ? 'selected' : '' }}>
                {{ $mentor }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('mentor_name')" class="mt-2" />
</div>

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text">
                                                        <i data-feather="mail" style="width: 16px; height: 16px;"></i>
                                                    </span>
                                                    <x-text-input type="email" class="form-control" name="email_address"
                                                        placeholder="example@email.com" :value="old('email_address', $application->email_address ?? Auth::user()->email)" required />
                                                    <x-input-error :messages="$errors->get('email_address')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <x-primary-button class="btn btn-primary">Submit</x-primary-button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript Section -->
                        @section('scripts')
                            <script>
                                function handleNationalityChange() {
                                    const nationality = document.getElementById('nationality').value;
                                    const sriLankanFields = document.getElementById('sriLankanFields');
                                    const otherNationalityFields = document.getElementById('otherNationalityFields');
                                    const nicNumber = document.getElementById('nic_number');
                                    const nicPhoto = document.getElementById('nic_photo');
                                    const passportNumber = document.getElementById('passport_number');
                                    const passportPhoto = document.getElementById('passport_photo');

                                    sriLankanFields.style.display = nationality === 'Sri Lanka' ? 'block' : 'none';
                                    otherNationalityFields.style.display = nationality === 'Other' ? 'block' : 'none';

                                    // Toggle required attributes based on nationality
                                    if (nationality === 'Sri Lanka') {
                                        nicNumber.required = true;
                                        nicPhoto.required = true;
                                        passportNumber.required = false;
                                        passportPhoto.required = false;
                                    } else if (nationality === 'Other') {
                                        nicNumber.required = false;
                                        nicPhoto.required = false;
                                        passportNumber.required = true;
                                        passportPhoto.required = true;
                                    } else {
                                        nicNumber.required = false;
                                        nicPhoto.required = false;
                                        passportNumber.required = false;
                                        passportPhoto.required = false;
                                    }
                                }

                                // Bootstrap Validation
                                document.addEventListener('DOMContentLoaded', function () {
                                    const form = document.querySelector('.needs-validation');

                                    form.addEventListener('submit', function (event) {
                                        if (!form.checkValidity()) {
                                            event.preventDefault();
                                            event.stopPropagation();
                                        }

                                        form.classList.add('was-validated');
                                    }, false);

                                    // Initialize nationality fields visibility and required attributes
                                    handleNationalityChange();
                                });
                            </script>
                            <script>
function updatePhonePrefix() {
    const countrySelect = document.getElementById('country');
    const phonePrefix = countrySelect.selectedOptions[0]?.dataset.code || '+94'; // Default to +94 if no country selected
    const prefixSpans = document.querySelectorAll('.phone-prefix');

    prefixSpans.forEach(span => {
        span.textContent = phonePrefix;
    });
}

function updateFields() {
    const country = document.getElementById('country').value;
    const districtSelect = document.getElementById('district');
    const provinceSelect = document.getElementById('province');
    const districtProvinceFields = document.querySelectorAll('.district-province-fields');

    if (country === 'Sri Lanka') {
        districtProvinceFields.forEach(field => field.style.display = 'block');
        districtSelect.setAttribute('required', 'required');
        provinceSelect.setAttribute('required', 'required');
    } else {
        districtProvinceFields.forEach(field => field.style.display = 'none');
        districtSelect.removeAttribute('required');
        provinceSelect.removeAttribute('required');
        districtSelect.value = '-';
        provinceSelect.value = '-';
    }
    updatePhonePrefix(); // Update phone prefix when country changes
}

// Trigger updates on page load based on preselected country
document.addEventListener('DOMContentLoaded', function () {
    updateFields();
    updatePhonePrefix();
});
</script>
                        @endsection
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection