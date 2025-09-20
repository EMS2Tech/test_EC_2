<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function __construct()
    {
        // Middleware applied in routes/web.php
    }

    public function create()
{
    $user = Auth::user();
    $application = Application::where('user_id', $user->id)->first();
    if ($application && $application->application_completed && $application->status !== 'Rejected') {
        return redirect()->route('profile.edit')->with('error', 'You have already submitted an application.');
    }
    $application = $application ?? new Application(['user_id' => $user->id]);

    // Countries array (name and phone code)
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

    ];

    return view('application.application', compact('application', 'countries'));
}

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if application already exists
        $application = Application::where('user_id', $user->id)->first();

        if ($application && $application->application_completed && $application->status !== 'Rejected') {
            return redirect()->route('course-application.create')->with('error', 'You have already submitted an application.');
        }

        // Countries array (needed for country code mapping)
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

        ];

        $rules = [
    'title' => 'required|in:Mr,Mrs,Miss,Rev',
    'full_name' => 'required|string|max:255',
    'name_with_initials' => 'required|string|max:255',
    'birthday' => 'required|date|before:today',
    'nationality' => 'required|in:Sri Lanka,Other',

    // Address
    'house_number' => 'nullable|string|max:50',
    'street_name' => 'required|string|max:255',
    'apartment' => 'nullable|string|max:255',
    'district' => Rule::requiredIf(function () use ($request) {
            return $request->input('country') === 'Sri Lanka';
        }),
    'province' => Rule::requiredIf(function () use ($request) {
            return $request->input('country') === 'Sri Lanka';
        }),
    'country' => 'required|string',

    // Phones (+94 format, store only 9 digits after +94)
    'contact_number' => 'required|string|regex:/^[0-9]{6,15}$/',
    'whatsapp_number' => 'nullable|string|regex:/^[0-9]{6,15}$/',
    'home_number' => 'nullable|string|regex:/^[0-9]{6,15}$/',
    'mentor_name' => 'required|string|max:255',
    'email_address' => 'required|email|max:255',
    'photograph' => 'required|image|mimes:jpeg,png,jpg|max:4096',
];


        // Conditional validation based on nationality
        if ($request->nationality === 'Sri Lanka') {
            $rules['nic_number'] = 'required|string|max:12';
            $rules['nic_photo'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:4096';
        } else {
            $rules['other_nationality'] = 'required|string|max:255';
            $rules['passport_number'] = 'required|string|max:20';
            $rules['passport_photo'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:4096';
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors(), 'input' => $request->all()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Use existing application if it exists, otherwise create new
        $application = $application ?? new Application(['user_id' => $user->id]);

        // Determine folder name based on nationality
        $folderName = $request->nationality === 'Sri Lanka' && $request->nic_number
            ? $request->nic_number
            : ($request->nationality === 'Other' && $request->passport_number
                ? $request->passport_number
                : ($application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time())))); // Fallback: use existing or user_id_timestamp

        // Ensure the folder exists with the applications prefix
        $storagePath = "applications/{$folderName}";
        if (!Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        // Handle file uploads with logging
        if ($request->hasFile('nic_photo')) {
            if ($request->file('nic_photo')->isValid()) {
                // Delete old NIC photo if exists
                if ($application->nic_photo) {
                    Storage::disk('public')->delete($application->nic_photo);
                }
                $application->nic_photo = $request->file('nic_photo')->store($storagePath, 'public');
                Log::info('NIC photo uploaded', ['path' => $application->nic_photo]);
            } else {
                Log::error('Invalid NIC photo upload', ['file' => $request->file('nic_photo')]);
                return redirect()->back()->with('error', 'Invalid NIC photo file.')->withInput();
            }
        }
        if ($request->hasFile('passport_photo')) {
            if ($request->file('passport_photo')->isValid()) {
                // Delete old passport photo if exists
                if ($application->passport_photo) {
                    Storage::disk('public')->delete($application->passport_photo);
                }
                $application->passport_photo = $request->file('passport_photo')->store($storagePath, 'public');
                Log::info('Passport photo uploaded', ['path' => $application->passport_photo]);
            } else {
                Log::error('Invalid passport photo upload', ['file' => $request->file('passport_photo')]);
                return redirect()->back()->with('error', 'Invalid passport photo file.')->withInput();
            }
        }
        if ($request->hasFile('photograph')) {
            if ($request->file('photograph')->isValid()) {
                // Delete old photograph if exists
                if ($application->photograph) {
                    Storage::disk('public')->delete($application->photograph);
                }
                $application->photograph = $request->file('photograph')->store($storagePath, 'public');
                Log::info('Photograph uploaded', ['path' => $application->photograph]);
            } else {
                Log::error('Invalid photograph upload', ['file' => $request->file('photograph')]);
                return redirect()->back()->with('error', 'Invalid photograph file.')->withInput();
            }
        }

        // Merge address fields into a single string
$addressParts = [
    $request->house_number,  // optional
    $request->street_name,   // required
    $request->apartment,     // optional
    $request->district,      // required
    $request->province,       // required
    $request->country       // required
];

// Filter out empty values
$address = implode(', ', array_filter($addressParts, fn($part) => !empty($part)));

// Add country code to phone numbers
        $countryCode = collect($countries)->firstWhere('name', $request->country)['code'] ?? '94';
        $prefix = '+' . $countryCode;

        // Fill application data
        $application->fill([
    'title' => $request->title,
    'full_name' => $request->full_name,
    'name_with_initials' => $request->name_with_initials,
    'birthday' => $request->birthday,
    'nationality' => $request->nationality,
    'nic_number' => $request->nationality === 'Sri Lanka' ? $request->nic_number : null,
    'other_nationality' => $request->nationality === 'Other' ? $request->other_nationality : null,
    'passport_number' => $request->nationality === 'Other' ? $request->passport_number : null,
    'address' => $address,
    'contact_number' => $prefix . ltrim($request->contact_number, '0'),
    'whatsapp_number' => $request->whatsapp_number ? $prefix . ltrim($request->whatsapp_number, '0') : null,
    'home_number' => $request->home_number ? $prefix . ltrim($request->home_number, '0') : null,
    'mentor_name' => $request->mentor_name,
    'email_address' => $request->email_address,
    'application_completed' => true,
    'status' => 'Pending', // Reset status to Pending on resubmission
]);

        $application->save();

        Log::info('Application submitted or updated', ['user_id' => $user->id, 'folder' => $storagePath, 'id' => $application->id]);

        return redirect()->route('profile.edit')->with('status', 'Application submitted successfully!');
    }

    public function updatePhotograph(Request $request)
    {
        $user = Auth::user();
        $application = Application::where('user_id', $user->id)->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'No application found for this user.'], 404);
        }

        $request->validate([
            'photograph' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        // Determine folder name (use existing nic_number or passport_number or fallback)
        $folderName = $application->nic_number ?: ($application->passport_number ?: ($user->id . '_' . time()));
        $storagePath = "applications/{$folderName}";
        if (!Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->makeDirectory($storagePath);
        }

        // Delete old photograph if exists
        if ($application->photograph) {
            Storage::disk('public')->delete($application->photograph);
        }

        // Store new photograph
        $path = $request->file('photograph')->store($storagePath, 'public');
        $application->photograph = $path;
        $application->save();

        Log::info('Profile photograph updated', ['user_id' => $user->id, 'path' => $path]);

        return response()->json([
            'success' => true,
            'newPhotographUrl' => asset('storage/' . $path)
        ]);
    }

    public function export(Request $request)
{
    $query = Application::select(
        'applications.id',
        'full_name',
        'contact_number',
        'email_address',
        'nic_number',
        'passport_number',
        'nationality',
        'status',
        'updated_by',
        'applications.created_at'
    )->leftJoin('users', 'applications.user_id', '=', 'users.id')->latest('applications.created_at');

    // Apply filters from the request
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nic_number', 'like', "%{$search}%")
              ->orWhere('passport_number', 'like', "%{$search}%")
              ->orWhere('applications.id', 'like', "%{$search}%")
              ->orWhere('email_address', 'like', "%{$search}%");
        });
    }

    if ($request->has('status') && $request->input('status') !== '') {
        $query->where('status', $request->input('status'));
    }

    if ($request->has('updated_by') && $request->input('updated_by') !== '') {
        $query->where('updated_by', $request->input('updated_by'));
    }

    // Apply date range filter
    if ($request->has('date_range')) {
        $now = \Carbon\Carbon::now('Asia/Colombo');
        switch ($request->input('date_range')) {
            case 'last_24h':
                $query->where('applications.created_at', '>=', $now->subHours(24));
                break;
            case 'last_7d':
                $query->where('applications.created_at', '>=', $now->subDays(7));
                break;
            case 'last_month':
                $query->where('applications.created_at', '>=', $now->subMonth());
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = \Carbon\Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = \Carbon\Carbon::parse($request->input('end_date'))->endOfDay();
                    if ($startDate->lte($endDate)) {
                        $query->whereBetween('applications.created_at', [$startDate, $endDate]);
                    }
                }
                break;
        }
    }

    $applications = $query->get();

    // Generate CSV content
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="applications_export_' . date('Ymd_His') . '.csv"',
    ];

    $callback = function () use ($applications) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['ID', 'Full Name', 'Contact Number', 'Email', 'NIC/Passport', 'Nationality', 'Status', 'Updated By', 'Created At']);

        foreach ($applications as $application) {
            $updatedByName = $application->updatedBy ? ($application->updatedBy->name ?? 'N/A') : 'N/A';
            $nicPassport = $application->nationality === 'Sri Lanka' ? ($application->nic_number ?? 'N/A') : ($application->passport_number ?? 'N/A');
            fputcsv($file, [
                sprintf('%.5d', $application->id ?? 0),
                $application->full_name ?? 'N/A',
                $application->contact_number ?? 'N/A',
                $application->email_address ?? 'N/A',
                $nicPassport,
                $application->nationality ?? 'N/A',
                $application->status ?? 'Not Complete',
                $updatedByName,
                $application->created_at ? $application->created_at->format('Y-m-d H:i:s') : 'N/A',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}