<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function shipping()
    {
        return view('pages.legal', [
            'title' => __('Shipping information'),
            'content' => nl2br(e(
                "Dostava se vrši putem BH Pošte i drugih dostavnih službi širom Bosne i Hercegovine.\n\n"
                . "Standardna cijena dostave iznosi " . \App\Support\Money::format(\App\Models\Setting::get('shipping_flat_rate', 5)) . ".\n"
                . (\App\Models\Setting::get('shipping_free_over') ? "Besplatna dostava za sve narudžbe iznad " . \App\Support\Money::format(\App\Models\Setting::get('shipping_free_over')) . ".\n\n" : "\n")
                . "Rok isporuke: 2–5 radnih dana.\n\n"
                . (\App\Models\Setting::get('shipping_note') ?: "")
            )),
        ]);
    }

    public function returns()
    {
        return view('pages.legal', [
            'title' => __('Returns and refunds'),
            'content' => nl2br(e(
                "Ako niste zadovoljni proizvodom, možete ga vratiti u roku od 14 dana od dana prijema.\n\n"
                . "Proizvod mora biti neoštećen, s originalnim etiketama.\n\n"
                . "Za povrat nas kontaktirajte na e-mail ili WhatsApp — pošaljemo Vam upute za slanje."
            )),
        ]);
    }

    public function privacy()
    {
        return view('pages.legal', [
            'title' => __('Privacy policy'),
            'content' => nl2br(e(
                "Poštujemo Vašu privatnost. Podatke koje unosite pri narudžbi (ime, adresa, telefon, e-mail) koristimo isključivo za obradu narudžbe i isporuku.\n\n"
                . "Ne prosljeđujemo Vaše podatke trećim licima osim dostavnoj službi.\n\n"
                . "Kolačiće koristimo samo za osnovnu funkcionalnost sajta (korpa, jezik)."
            )),
        ]);
    }

    public function terms()
    {
        return view('pages.legal', [
            'title' => __('Terms of service'),
            'content' => nl2br(e(
                "Naručivanjem sa " . \App\Models\Setting::get('brand_name', 'SinonimDesign') . " sajta prihvatate ove uslove.\n\n"
                . "Sva plaćanja su pouzećem — plaćate prilikom preuzimanja pošiljke.\n\n"
                . "Cijene su prikazane u KM (bosanska marka) sa svim porezima uključenim.\n\n"
                . "Za sva pitanja kontaktirajte nas."
            )),
        ]);
    }
}
