@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.datenschutz', 'class="active"')

@section('content')
<style>
main {
    font-size: 10pt;
    hyphens:auto;
    counter-reset: kontext 0 datum 0;
    text-align: justify;
}

h1 {
    font-size: 18pt;
}

.kontext > h1::before {
  counter-increment: kontext;
  content: "K" counter(kontext) " ";
  font-weight: normal;
}

.datum > h1::before {
  counter-increment: datum;
  content: "D" counter(datum) " ";
  font-weight: normal;
}

.kontext h1 {
   font-size: 16pt;
   margin-bottom: inherit;
}
.kontext h2 {
   font-size: 14pt;
   margin-bottom: inherit;
}
.datum h1 {
   font-size: 12pt;
   margin-bottom: inherit;
}
.datum h2 {
   font-size: 11pt;
   margin-bottom: inherit;
   margin-top: 5pt;
}

.kontext-list {
    list-style: none;
}
.datum-list {
    list-style: none;
}

samp {
    hyphens:none;
    font-size: 8pt;
}
</style>
	   <div>
    <h1>Datenschutz bei MetaGer/SUMA-EV</h1>
		Für maximale Transparenz listen wir auf, welche Daten wir von Ihnen erheben und wie wir sie verwenden.
		Der Schutz Ihrer Daten ist uns wichtig und Ihnen sollte er das auch sein.
		<br /><strong>Lesen Sie diese Erklärung bitte gründlich; es ist in Ihrem Interesse.</strong>
		</div>
        <div>
    <h1>Verantwortliche und Ansprechpartner</h1>
		MetaGer und verwandte Dienste werden betrieben vom <a href="https://suma-ev.de/impressum.html">SUMA-EV</a>, der auch Verfasser dieser Erklärung ist.
		Mit „Wir“ ist in dieser Erklärung in der Regel der SUMA-EV gemeint.
		<br />Unsere Kontaktdaten finden Sie in unserem <a href="https://suma-ev.de/impressum.html">Impressum</a>. Per E-Mail sind wir unter der Adresse  <a href="mailto:office@suma-ev.de">office@suma-ev.de</a> zu erreichen.
		</div>
    <div>
    <h1>Grundsätze</h1>
		Wir haben uns als gemeinnütziger Verein dem freien Wissenszugang verschrieben. Da wir wissen, dass freie Recherche nicht mit Massenüberwachung vereinbar ist, nehmen wir auch Datenschutz sehr ernst. Wir verarbeiten schon immer nur die Daten, die zum Betrieb unserer Dienste unbedingt nötig sind. Datenschutz ist bei uns immer der Standard. Profiling — also die automatische Erstellung von Nutzerprofilen — betreiben wir nicht.
		</div>
    <div>
    <h1>Anfallende Daten nach Kontext</h1>
    <ol>
        <li class="kontext-list">
            <article class="kontext">
                <h1>Benutzung der Websuchmaschine MetaGer</h1>
                Bei der Nutzung unserer Websuchmaschine MetaGer über deren Web-Formular oder durch deren OpenSearch-Schnittstelle fallen folgende Daten an:
                <ol class="datum-list">
                    <li>
                        <article class="datum">
                            <h1 id="ip-address">Internet-Protokoll-Adresse</h1>
														Die Internet-Protokoll-Adresse (nachfolgend kurz IP) wird zwingend benötigt, um Webdienste wie MetaGer zu nutzen. Diese IP identifiziert in Kombination mit einem Datum – ähnlich einer Telefonnummer – einen Internetzugang sowie dessen Inhaber eindeutig.
														<br />Im Allgemeinen sind die ersten drei (von insgesamt vier) Blöcken einer IP nicht personenbezogen.
														Werden hintere Blöcke der IP gekürzt, identifiziert die gekürzte Adresse den ungefähren geografischen Bereich um den Internet-Anschluss.
														<h2>Beispiele (vollständige IP-Adresse)</h2>
														<samp>154.67.88.47</samp><br />
														<samp>82.159.53.49</samp>
														<h2>Beispiele (nur die ersten zwei Blöcke)</h2>
														<samp>154.67.0.0</samp><br />
														<samp>82.159.0.0</samp>
														<h2>Was macht MetaGer/SUMA-EV damit?</h2>
														<ol>
															<li>Um unseren Dienst vor Überlastung zu schützen, müssen wir die Anzahl der Suchanfragen pro Internetanschluss begrenzen.
																Allein für diesen Zweck speichern wir die vollständige IP-Adresse und einen Zeitstempel für maximal 96 Stunden.
																Werden auffällig viele Suchen von einer IP durchgeführt, wird diese IP vorübergehend (maximal 96 Stunden nach der letzen Suche) in einer Sperrliste gespeichert.
																Anschließend wird die IP gelöscht.
															</li>
														  <li>Neben Spenden und Mitgliedsbeiträgen müssen wir unseren Betrieb durch nicht-personalisierte Werbung auf der Ergebnisseite finanzieren. Um diese Werbung zu erhalten, geben wir die ersten beiden Blöcke der IP in Verbindung mit Teilen vom sog. <a href="#user-agent">User-Agent</a> an unsere Werbepartner.
													   </li>
												</ol>
												<h2>Welche Rechte habe ich als Nutzer?</h2>
												Da die vollständige Form der Internet-Protokoll-Adresse personenbezogen ist, haben Sie insbesondere die folgenden Rechte:
													<a href="#ihrerechte">Rechte ansehen</a>
                        </article>
                    </li>
                    <li>
                        <article class="datum">
                            <h1 id="search-request">Eingegebene Suchanfrage</h1>
                            Eingegebene Suchbegriffe sind zwingend notwendig für eine Websuche.
														Aus ihnen können in der Regel keine personenbezogenen Daten gewonnen werden; unter anderem, weil sie keine feste Struktur aufweisen.
														<h2>Beispiele</h2>
                            <samp>Wasserverbrauch duschen</samp><br />
                            <samp>Liedtext Auf einem Baum ein Kuckuck</samp><br />
                            <samp>Grakvaloth</samp><br />
                            <samp>WHO Abkürzung</samp>
														<h2>Was macht MetaGer/SUMA-EV damit?</h2>
														<ol>
															<li>Als integraler Bestandteil der Metasuche wird die Suchanfrage an unsere Partner übertragen, um Suchergebnisse zur Anzeige auf der Ergebisseite zu erhalten. Die erhaltenen Ergebnisse inklusive dem Suchbegriff werden für wenige Stunden zur Anzeige vorgehalten.
															</li>
														</ol>
                        </article>
                    </li>
                    <li>
                        <article class="datum">
													<h1 id="user-agent">User-Agent-Bezeichner</h1>
                            Beim Aufruf einer Webseite sendet ihr Browser automatisch eine Kennung, in der Regel mit Daten über den verwendeten Browser und das verwendete Betriebssystem.
														Diese Browser-Kennung (der sog. User-Agent) kann von Webseiten zum Beispiel verwendet werden, um Mobilgeräte zu erkennen und diesen eine angepasste Ausgabe zu präsentieren.
                            <h2>Beispiel</h2>
                            <samp>Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:59.0) Gecko/20100101 Firefox/59.0</samp>
														<h2>Was macht MetaGer/SUMA-EV damit?</h2>
														<ol>
															<li>Neben Spenden und Mitgliedsbeiträgen müssen wir unseren Betrieb durch nicht-personalisierte Werbung auf der Ergebnisseite finanzieren. Um diese Werbung zu erhalten, geben wir Teile des User-Agents in Verbindung mit den ersten beiden Blöcken der sog. <a href="#ip-address">IP-Adresse</a> an unsere Werbepartner.
															</li>
														</ol>
                        </article>
                    </li>
                    <li>
                        <article class="datum">
                            <h1>Nutzerpräferenzen</h1>
                            Neben Formulardaten und User-Agent werden vom Browser oft weitere Daten übertragen.
														Dazu gehören Sprachauswahl, Sucheinstellungen, Accept-Header, Do-Not-Track-Header und weitere.
														<h2>Beispiele</h2>
                            <samp>interface=de sprachfilter=all fokus=web</samp><br />
                            <samp>interface=de sprachfilter=de fokus=nachrichten</samp><br />
                            <samp>interface=en sprachfilter=en fokus=web</samp>
														<h2>Was macht MetaGer/SUMA-EV damit?</h2>
														<ol>
															<li>Wir verwenden diese Daten (z.B. Spracheinstellungen), um die jeweilige Suchanfrage zu beantworten.</li>
															<li>Einige dieser Daten speichern wir nicht-personenbezogen zu statistischen Zwecken.</li>
														</ol>
                        </article>
                    </li>
                </ol>
            </article>
            <article class="kontext">
                <h1 id="contact">Nutzung des Kontaktformulars</h1>
                Bei der Nutzung des MetaGer-Kontaktformulars fallen folgende Daten an, die wir zu Referenzzwecken bis 2 Monate nach Abschluss Ihres Anliegens speichern:
                <ol class="datum-list">
                    <li>
                        <article class="datum">
                            <h1>Kontaktdaten</h1>
                            Hierunter fällt der von Ihnen angegebene Name (Vor- und Nachname), sowie Ihre E-Mail Adresse.
                            Diese Daten nutzen wir ausschließlich, um Ihnen zu antworten und geben Sie unter keinen Umständen weiter an Dritte.
                            <h2>Beispiele</h2>
                            <samp>Max Mustermann, mail@example.com</samp><br />
                            <samp>Erika Musterfrau, erika_m@domain.de</samp><br />
                        </article>
                    </li>
                    <li>
                        <article class="datum">
                            <h1>Nachricht</h1>
                            Die hier eingegebene Nachricht wird an uns übertragen und zur Bearbeitung Ihres Anliegens genutzt.
                            <h2>Beispiele</h2>
                            <samp>Feedback zu MetaGer</samp><br />
                            <samp>MetaGer als Browser-PlugIn</samp><br/>
                        </article>
                    </li>
                </ol>
            </article>
            <article class="kontext">
                <h1 id="donation">Nutzung des Spendenformulars</h1>
                Die folgenden, im Spendenformular übermittelten, Daten werden 2 Monate zur Bearbeitung gespeichert:
                <ol class="datum-list">
                    <li>
                        <article class="datum">
                            <h1>Kontaktdaten</h1>
                            Hierunter fällt der von Ihnen angegebene Name (Vor- und Nachname), sowie Ihre E-Mail Adresse.
                            Diese Daten nutzen wir ausschließlich für eventuelle Rückfragen und geben Sie unter keinen Umständen weiter an Dritte.
                            <h2>Beispiele</h2>
                            <samp>Max Mustermann, mail@example.com</samp><br />
                            <samp>Erika Musterfrau, erika_m@domain.de</samp><br />
                        </article>
                    </li>
                    <li>
                        <article class="datum">
                            <h1>Zahlungsdaten</h1>
                            Die Zahlungsdaten werden ausschließlich zur Abwicklung der Spende genutzt und unter keinen Umständen an Dritte weitergegeben.
                        </article>
                    </li>
                    <li>
                        <article class="datum">
                            <h1>Nachricht (optional)</h1>
                            Die hier eingegebene Nachricht wird an uns übertragen und bei der Bearbeitung Ihrer Spende berücksichtigt.
                        </article>
                    </li>
                </ol>
            </article>
            <article class="kontext">
                <h1>Einfache Nutzung des Webangebotes</h1>
	                            Beim Besuch von Webseiten der Domain „suma-ev.de“ werden folgende Daten erhoben und bis zu einer Woche gespeichert:
                                <ul>
                                    <li>Ihre IP-Adresse</li>
                                    <li>Name und URL der abgerufenen Datei</li>
                                    <li>Datum und Uhrzeit des Zugriffs</li>
                                    <li>der von Ihnen gesendete Referrer</li>
                                    <li>der von Ihnen gesendete User-Agent</li>
                                </ul>
                Wir verwenden die genannten Daten, um die Funktionsfähigkeit der Webseite sicherzustellen und uns vor Angriffen zu schützen. Die Rechtsgrundlage für die Verarbeitung ist damit ein berechtigtes Interesse nach Art. 6 Abs. 1 lit. f DSGVO.
                <br/>
                Auf den anderen Webseiten unserer Domains verarbeiten wir die erhobenen Daten nur zur Beantwortung von Anfragen und im Rahmen der anderen Punkte dieser Datenschutzerklärung.
                <br/>
                Auf der Startseite unseres Dienstes MetaGer verwenden wir den von Ihnen übertragenen User-Agent, um Ihnen die passende PlugIn-Installationsanleitung zu Ihrem Browser anzuzeigen.

            </article>
            <article class="kontext">
                <h1>Anmeldung für den SUMA-EV-Newsletter</h1>
                Um Sie über unsere Tätigkeiten auf dem Laufenden zu halten, bieten wir einen E-Mail-Newsletter an. Wir speichern dafür bis zu Ihrer Abmeldung folgende Daten:
                <ol class="datum-list">
                    <li>
                        <article class="datum">
                            <h1>Kontaktdaten</h1>
                            Hierunter fällt der von Ihnen angegebene Name (Vor- und Nachname), sowie Ihre E-Mail Adresse.
                            Diese Daten nutzen wir ausschließlich für den Versand des Newsletters und geben Sie unter keinen Umständen weiter an Dritte.
                            <h2>Beispiele</h2>
                            <samp>Max Mustermann, mail@example.com</samp><br />
                            <samp>Erika Musterfrau, erika_m@domain.de</samp><br />
                        </article>
                    </li>
                </ol>
            </article>
            <article class="kontext">
                <h1>Nutzung von Maps.MetaGer.de</h1>
                Bei der Nutzung des MetaGer-Kartendienstes fallen folgende Daten an:
                <ol>
                    <br/>
                    <li><a href="#ip-address">IP-Adresse</a>: Wird nicht gespeichert oder weitergegeben.</li>
                    <li><a href="#user-agent">User-Agent</a>: Wird nicht gespeichert oder weitergegeben.</li>
                    <li><a href="#search-request">Suchanfrage</a>: Wird nicht gespeichert oder weitergegeben.</li>
                    <li>Ortungsdaten: Werden nicht gespeichert oder weitergegeben.</li>
                </ol>
            </article>
            <article class="kontext">
                <h1>Nutzung des anonymisierenden Proxy</h1>
                Bei der Nutzung des anonymisierenden Proxy fallen folgende Daten an:
                <ol>
                    <br/>
                    <li><a href="#ip-address">IP-Adresse</a>: Wird nicht gespeichert oder weitergegeben.</li>
                    <li><a href="#user-agent">User-Agent</a>: Wird nicht gespeichert oder weitergegeben.</li>
                </ol>
            </article>
            <article class="kontext">
                <h1>Nutzung der Zitat-Suche</h1>
                    Der eingegebene Suchbegriff wird genutzt, um in der Zitat-Datenbank nach Ergebnissen zu suchen.
                    Im Gegensatz zur <a href="#search-request">Websuche</a> mit MetaGer, ist die Weitergabe des Suchbegriffes an Dritte nicht erforderlich, da sich die Zitat-Datenbank auf unserem Server befindet.
                    Andere Daten werden nicht gespeichert oder weitergegeben.
            </article>
            <article class="kontext">
                <h1>Nutzung des Assoziators</h1>
                    Der Assoziator nutzt den Suchbegriff, um die damit assoziierten Begriffe zu bestimmen und anzuzeigen.
                    Andere Daten werden nicht gespeichert oder weitergegeben.
            </article>
            <article class="kontext">
                <h1>Nutzung der MetaGer-App</h1>
                   Die Nutzung der MetaGer-App ist gleichzubehandeln mit der Verwendung von MetaGer über einen Webbrowser.
            </article>
            <article class="kontext">
                <h1>Nutzung des MetaGer-Plugin</h1>
				Bei der Nutzung des MetaGer-Plugin fallen folgende Daten an:
                <ol>
                    <br/>
                    <li><a href="#ip-address">IP-Adresse</a>: Wird nicht gespeichert oder weitergegeben.</li>
                    <li><a href="#user-agent">User-Agent</a>: Wird nicht gespeichert oder weitergegeben.</li>
                </ol>
            </article>
        </li>
    </ol>
    </div>
    <div>
        <h1>Hosting</h1>
        Die Webseiten unter der Domain „suma-ev.de“ werden bei der Intares GmbH gehostet und administriert. Die übrigen Dienste werden von uns, dem SUMA-EV, administriert und auf angemieteter Hardware bei der Hetzner Online GmbH betrieben.
    </div>
    <div>
    <h1>Rechtsgrundlage zur Verarbeitung</h1>
		Als Rechtsgrundlage zur Verarbeitung Ihrer personenbeziehbaren Daten dient uns entweder Art. 6 Abs. 1 lit. a DSGVO, wenn Sie der Verarbeitung durch Nutzung unserer Dienste zustimmen, oder Art. 6 Abs. 1 lit. f DSGVO, wenn die Verarbeitung für die Wahrung unserer berechtigeten Interessen nötig ist, oder eine andere Rechtsgrundlage, falls wir Ihnen diese gesondert mitteilen.
	</div>
   <div>
    <h1 id="ihrerechte">Ihre Rechte als Nutzer (und unsere Pflichten)</h1>
        Damit Sie Ihre personenbezogenen Daten auch schützen können, klären wir Sie (gemäß Art. 13 DSGVO) auf, dass Sie über die folgenden Rechte verfügen:
        <ol>
            <li><b>Auskunftsrecht:</b></li>
            <article class="kontext">
            Sie haben das Recht (Art. 15 DSGVO), jederzeit von uns Auskunft zu verlangen, ob und wenn ja welche Ihrer Daten wir (metager.de und SUMA-EV) über Sie besitzen. Wir werden Ihnen so schnell wie möglich, also binnen Tagen, eine vollumfängliche Kopie gemäß Art. 15 Absatz 3 Unterabsatz 1 DSGVO der bei uns über Sie gespeicherten
            oder anderweitig verwahrten Daten zukommen lassen. Wir bevorzugen hierfür den elektronischen Weg gemäß Art. 15 Absatz 3 Unterabsatz 3 DSGVO; hierzu werden wir Ihre E-Mailadresse für die Zeit der Abwicklung speichern. Bitte informieren Sie uns, wenn Sie die Information ausdrücklich in Papierform haben wollen.
            </article>
            <li><b>Recht auf Berichtigung und Ergänzung:</b></li>
            <article class="kontext">
            Gemäß Artikel 16 DSGVO. Sollten wir fehlerhafte Daten über Sie gespeichert haben, so können Sie bestimmen, dass diese berichtigt werden. Dies gilt auch für fehlende Bestandteile, hier haben Sie das Recht auf Ergänzung.
            </article>
            <li><b>Recht auf Löschung:</b></li>
            <article class="kontext">
            Gemäß Artikel 17 DSGVO
            </article>
            <li><b>Recht auf Einschränkung der Verarbeitung:</b></li>
            <article class="kontext">
            Gemäß Artikel 18 DSGVO; wenn Sie uns zum Beispiel aufgefordert haben, Daten von Ihnen zu löschen oder zu verändern, dann können Sie uns für die Zeit, die wir dafür benötigen, ein Verarbeitungsverbot auferlegen. Dies ist unabhängig davon möglich, ob wir die in Frage stehenden Daten letztlich ändern, löschen, etc.
            </article>
            <li><b>Recht auf Beschwerde:</b></li>
            <article class="kontext">
            Gemäß Artikel 13 Absatz 2 Buchstabe d) DSGVO können Sie sich bei der Datenschutzbeauftragten des Landes Niedersachsen über uns beschweren. Im Netz: <a href="https://www.lfd.niedersachsen.de/startseite/">Datenschutzbeauftragte</a>
            </article>
            <li><b>Recht auf Widerspruch gegen die Verarbeitung:</b></li>
            <article class="kontext">
            Gemäß Artikel 21 DSGVO; wenn Sie zum Beispiel auf einer Liste stehen und dort auch stehen wollen, können Sie trotzdem die Verarbeitung oder Weiterverarbeitung dieser Daten verbieten.
            </article>
            <li><b>Recht auf Datenübertragbarkeit:</b></li>
            <article class="kontext">
            Gemäß Artikel 20 DSGVO; dies bedeutet, dass wir verpflichtet sind, Ihnen die angefragten Daten in einer lesbaren, ggfs. maschinenlesbaren oder auch üblichen Art zu übermitteln, so dass Sie in der Lage wären, die Daten, so wie sie sind, einer anderen Person zugänglich zu machen (zu übermitteln).
            </article>
            <li><b>Mitteilungspflicht im Zusammenhang mit der Berichtigung oder Löschung personenbezogener Daten oder der Einschränkung der Verarbeitung:</b></li>
            <article class="kontext">
            Gemäß Artikel 19 DSGVO; falls wir Daten, die Sie uns anvertraut haben, Dritten zugänglich gemacht haben sollten (was wir niemals tun), wären wir verpflichtet, jenen mitzuteilen, dass wir auf Ihre Veranlassung hin eine Löschung, Änderung u.s.w. durchgeführt haben.
            </article>
        </ol>
        Zur Wahrnehmung dieser Rechte genügt es, uns eine <b>E-Mail an office@suma-ev.de</b> zu schreiben. Sollten Sie die Briefform bevorzugen, senden Sie uns Post an unsere Büroadresse:
        <br />
        <br />SUMA-EV
        <br />Röselerstraße 3
        <br />30159 Hannover
    </div>
    <div>
    <h1>Änderungen an dieser Erklärung</h1>
		Wie unsere Angebote ist auch diese Datenschutzerklärung einem ständigen Wandel unterworfen. Sie sollten sie daher regelmäßig erneut lesen.
        <br />Die vorliegende Version unserer Datenschutzerklärung trägt folgendes Datum: 2018-05-24
		</div>
@endsection
