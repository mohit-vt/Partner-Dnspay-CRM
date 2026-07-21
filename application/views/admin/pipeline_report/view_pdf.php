<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h2>Application #<?= $pipeline_report['application_id'] ?? '' ?></h2>
<hr>

<div class="section" style="margin-bottom:20px;">
    <h3 style="background:#f0f0f0; padding:5px;">Personal Details</h3>
    <table style="width:100%; border-collapse:collapse; font-size:12px;">
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Lead Source</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['lead_source'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Region</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['region'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Application Date</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['application_date'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Application IP</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['application_ip'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Company Name</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['company_name'] ?? '' ?></td>
        </tr>
    </table>
</div>

<div class="section" style="margin-bottom:20px;">
    <h3 style="background:#f0f0f0; padding:5px;">Business (KYB) Details</h3>
    <table style="width:100%; border-collapse:collapse; font-size:12px;">
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Legal Name</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['legal_name'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">DBA</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['dba'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Business Address</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['business_address'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">Website</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['website_url'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">MCC Code</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['mcc_code'] ?? '' ?></td>
        </tr>
        <tr>
            <th style="padding:5px; border:1px solid #ddd;">TIN/EIN</th>
            <td style="padding:5px; border:1px solid #ddd;"><?= $pipeline_report['tin_ein'] ?? '' ?></td>
        </tr>
    </table>
</div>

<div class="section" style="margin-bottom:20px;">
    <h3 style="background:#f0f0f0; padding:5px;">Notes</h3>
    <p style="font-size:12px;"><?= nl2br($pipeline_report['notes'] ?? '') ?></p>
</div>
