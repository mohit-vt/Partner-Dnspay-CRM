<?php

defined('BASEPATH') or exit('Doğrudan komut dosyası erişimine izin verilmez');


# Meeting labels

$lang['zmm_instant_label'] = 'Anlık Toplantı';

$lang['zmm_scheduled_label'] = 'Planlanmış Toplantı';

$lang['zmm_recurring1_label'] = 'Sabit bir süre olmadan yinelenen toplantı';

$lang['zmm_recurring2_label'] = 'Sabit süreli yinelenen toplantı';


# Other

$lang['zmm_settings_yes'] = 'Evet';

$lang['zmm_settings_no'] = 'Hayır';

$lang['zmm_module_name'] = 'Zoom Toplantı Yöneticisi';

$lang['zmm_module_name_menu'] = 'Zoom Toplantıları';

$lang['zmm_create_meeting'] = 'Bir Toplantı Planla';

$lang['zmm_no_meetings_yet'] = 'Henüz toplantınız yok. ';

$lang['zmm_zoom_login'] = 'Zoom ile giriş yap';

$lang['zmm_meeting_deleted'] = 'Toplantınız başarıyla silindi';

$lang['zmm_meeting_created'] = 'Yeni toplantınız başarıyla oluşturuldu';

$lang['zmm_shedule_label'] = 'Takvim';

$lang['zmm_back_to_meetings'] = 'Toplantılara Dön';

$lang['zmm_topic_label'] = 'Konu';

$lang['zmm_description_label'] = 'Açıklama (opsiyonel)';

$lang['zmm_when_date'] = 'Ne Zaman';

$lang['zmm_join_label'] = 'Katıl';

$lang['zmm_contacts'] = 'Kişiler';

$lang['zmm_web_url_label'] = 'Web url';

$lang['zmm_password_label'] = 'Şifre';

$lang['zmm_type_label'] = 'Tip';

$lang['zmm_start_time_label'] = 'Başlama Zamanı';

$lang['zmm_timezone_label'] = 'Saat Dilimi';

$lang['zmm_created_at_label'] = 'Oluşturan';

$lang['zmm_general'] = 'Genel';

$lang['zmm_additional_settings'] = 'Ek Ayarlar';

$lang['zmm_app_url_label'] = 'App url';

$lang['zmm_meeting_duration'] = 'Süresi';

$lang['zmm_create_note'] = 'Not: Oluşturulan tüm toplantılar Zoom Sunucuları Veritabanında saklanacaktır';

$lang['zmm_optional'] = 'isteğe bağlı';

$lang['zmm_timezone'] = 'Saat dilimi';

$lang['zmm_hour'] = 'Saat';

$lang['zmm_minutes'] = 'Dakika';

$lang['zmm_hours_and'] = 'Saat ve';

$lang['zmm_hours'] = 'Saat';

$lang['zmm_no_description'] = 'Açıklama, yönetici tarafından ayarlanmadı';

$lang['zmm_select_participants'] = 'Katılımcıları seçin';


#Zoom Settings labels

$lang['zmm_join_before_host'] = 'Toplantı sahibi toplantıyı başlatmadan önce katılımcıların toplantıya katılmasına izin verin.';

$lang['zmm_host_video'] = 'Toplantı sahibi toplantıya katıldığında videoyu başlat.';

$lang['zmm_participant_video'] = 'Katılımcılar toplantıya katıldığında videoyu başlat.';

$lang['zmm_mute_upon_entry'] = 'Girişte katılımcıları sessize al.';

$lang['zmm_waiting_room'] = 'Bekleme odasını etkinleştir.';

$lang['zmm_app_id_label'] = 'Client ID';

$lang['zmm_app_secret_label'] = 'Client Secret';

$lang['zmm_app_redirect_url_label'] = 'Yakınlaştırma Yetkilendirme Yönlendirme URI';


# Zoom Account Info

$lang['zmm_user_type'] = 'Kullanıcı Tipi: Temel';

$lang['zmm_user_basic_info'] = '2 den fazla katılımcınız varsa, ev sahipliği yaptığınız toplantılar 40 dakika ile sınırlı olacaktır.';

$lang['zmm_participants_account_info'] = 'Not: Temel Kullanıcı türü bir hesap kullanıyorsunuz, örn. Bedava hesap. Eklediğiniz katılımcılar / kayıt sahipleri, Zoom toplantınıza katılımcılar olarak eklenmez, ancak Zoom Toplantı Yöneticisi modülü, katılımcılarınızı Perefex CRM veritabanına eklemenizi ve ayrıca katılımcı veya kayıt sahibi olarak atanan (eklenen) tümüne e-posta göndermenizi sağlar. toplantıya. Ayrıca WEB ve APP urlsi katılımcılara toplantıya kolayca katılabilmeleri için gönderilecektir.';


# Zoom View Meeting

$lang['zmm_meeting_info'] = 'Toplantı Bilgileri';

$lang['zmm_started'] = 'Başladı';

$lang['zmm_desc_agenda'] = 'Açıklama / Gündem';

$lang['zmm_meeting_status'] = 'Durum';

$lang['zmm_start_url_info'] = 'Başlangıç ​​URLsine tıkladıktan sonra tarayıcınız yeni sekme açacaktır, sekme tamamen yüklendikten sonra sekmeyi kapatabilirsiniz. Ardından URLye Katıl (Web) öğesine tıklayarak toplantıya katılabilirsiniz.';

$lang['zmm_meeting_start_url'] = 'Başlangıç ​​URLsi';

$lang['zmm_meeting_not_set'] = 'Ayarlanmadı';

$lang['zmm_meeting_type'] = 'Toplantı Tipi';

$lang['zmm_meeting_host_video'] = 'Video Barındırma';

$lang['zmm_meeting_participant_video'] = 'Katılımcı Videosu';

$lang['zmm_join_before_host'] = 'Toplantı Sahibinden Önce Katıl';

$lang['zmm_mute_upon_entry'] = 'Girişten Sonra Sesi Kapat';

$lang['zmm_waiting_room'] = 'Bekleme Odası';

$lang['zmm_meeting_auth'] = 'Toplantı Kimlik Doğrulaması';

$lang['zmm_join_web_url'] = 'URLye katıl (Web)';

$lang['zmm_password_info'] = 'Toplantıyı oluştururken belirlediğiniz parola bu değilse, temel bir ücretsiz hesap kullandığınız anlamına gelir ve parola her zaman ayarlanır, ancak toplantıya kaydolanların / katılımcılarınızın, tarafından ayarlanmış olsa bile parolayı girmeleri gerekmeyecektir. varsayılan. Toplantıyı oluştururken belirlediğiniz şifre bu değilse, tüm üyelerin toplantıya katılmak için şifreyi girmesi gerekecektir.';

$lang['zmm_create_permissions'] = 'Giriş yaptınız ancak Zoom Toplantıları Modülü için <b> oluşturma izniniz </b> yok, lütfen daha fazla ayrıntı için bir yönetici ile iletişime geçin.';

$lang['zmm_viewing_notes'] = 'Konuyla ilgili toplantı için notları görüntüleme: ';

$lang['zmm_edit_history_notes'] = 'Notlar';

$lang['zmm_meeting_notes_updated'] = 'Toplantı notları başarıyla güncellendi';

