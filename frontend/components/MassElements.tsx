// import style from "./style.module.css"
import { MassElem, MassElemSectionProps, OrdinariumProcessorProps, OrdinariumProps, SongProps } from "../types"
import React, { ReactNode, useContext, useState, useEffect } from "react";
import { slugAndDePL } from "../helpers";
import { Button, DummyInput } from "./Interactives";
import { MModContext, ShowLyricsContext } from "../pages/Set";
import { SheetMusicRender } from "./SheetMusicRender";
import axios from "axios";
import { SongRender } from "./SongRender";

export function MassElemSection({id, uneresable = false, children}: MassElemSectionProps){
  const MMod = useContext(MModContext);
  const is_communion = id.match(/sCommunion/) && document.getElementById("sAdoration");

  return(
    <section id={id} className="massElemSection">
      <div className="massElemEditorElement show-after-click massElemEraser flex-right">
        {is_communion && <Button onClick={() => document.getElementById("sAdoration")?.scrollIntoView({behavior: "smooth", block: "center"})}>»U</Button>}
        {!uneresable && <Button onClick={() => MMod.eraseMassElem(id)}>×</Button>}
      </div>
      <div className="massElemEditorElement show-after-click massElemAdder flex-right">
        <Button onClick={() => MMod.addMassElem(id)}>+</Button>
      </div>
      {children}
    </section>
  )
}

export function SongLyrics({lyrics, forceLyricsVariant}: {lyrics: string | string[] | null, forceLyricsVariant?: number}){
  const showLyrics = useContext(ShowLyricsContext);
  const [variant, setVariant] = useState(0);
  const changeVariant = (new_variant: number) => setVariant(new_variant);

  const render_variants = Array.isArray(lyrics) && lyrics.length > 1;
  const lyrics_ready = Array.isArray(lyrics) ? lyrics[forceLyricsVariant ?? variant] ?? lyrics[0] : lyrics;

  const lyrics_processed = lyrics_ready?.replace(/(\*\*|--)\s*\r?\n/g, '</span><br>')
    .replace(/\*\s*\r?\n/g, `<span class="chorus">`)
    .replace(/-\s*\r?\n/g, `<span class="tabbed">`)
    .replace(/_(.{1,5})_/g, '<u>$1</u>')
    .replace(/(\d+)\.\s*\r?\n/g, "<li value='$1'>")
    .replace(/\s*\r?\n/g, "<br />");

  return showLyrics
    ? <>
      {render_variants && forceLyricsVariant === undefined &&
        <div className="flex-right center">
        {lyrics.map((var_lyrics, var_no) =>
          <Button key={var_no}
            className={[variant === var_no && 'accent-border', 'slick'].filter(Boolean).join(" ")}
            onClick={() => changeVariant(var_no)}>
            {var_no + 1}
          </Button>)}
      </div>}
      <ol className="lyrics" dangerouslySetInnerHTML={{ __html: lyrics_processed ?? ""}} />
    </>
    : <></>;
}

export function PsalmLyrics({lyrics}: {lyrics: string | null}){
  let lyrics_split = lyrics?.split(/\s*%%%\s*/) ?? [];
  const render_variants = lyrics_split.length > 1;
  const [variant, setVariant] = useState(0);
  const changeVariant = (new_variant: number) => setVariant(new_variant);

  /**
   * account for variant labels:
   * blocks prefaced with line `//{LABEL}` will translate to label `{LABEL}`
   */
  const lyrics_split_labels = lyrics_split.map((variant, var_no) => {
    const variant_label = variant.match(/^\/\/(.*)\s+/)
    return (variant_label) ? variant_label[1] : (var_no + 1)
  })
  lyrics_split = lyrics_split.map((variant, var_no) =>
    (var_no + 1) === lyrics_split_labels[var_no]
      ? variant
      : variant.replace(/\/\/.*\s+/g, "")
  )

  return(<>
    {render_variants &&
      <div className="flex-right center">
      {lyrics_split.map((var_lyrics, var_no) =>
        <Button key={var_no}
          className={[variant === var_no && 'accent-border'].filter(Boolean).join(" ")}
          onClick={() => changeVariant(var_no)}>
          {lyrics_split_labels[var_no]}
        </Button>)}
      </div>}
      <div className="psalm">
      {lyrics_split[variant]?.split(/\r?\n\r?\n/).map((out, i) =>
        <p key={i} dangerouslySetInnerHTML={{ __html: out.replace(/\r?\n/g, "<br>")}} />
      )}
      </div>
  </>)
}

export function Antiphon({call, resp, respMelody}: {call: string, resp: string, respMelody?: string}){
  if (respMelody) {
    resp += `<span> (🎼)</span>`;
  }

  return(
    <div className="flex-right antyfona">
      <div className="ksiadz">{call}</div>
      <div>→</div>
      <div className="wierni-container">
        <div dangerouslySetInnerHTML={{ __html: resp }}></div>
        {respMelody && <div className="melody">
            <SheetMusicRender notes={respMelody} />
        </div>}
      </div>
    </div>
  )
}

export function Alternative({children}: {children: ReactNode}){
  return(
    <div className="alternative">
      <h4>Wybierz jedno:</h4>
      {children}
    </div>
  )
}

export function OrdinariumProcessor({code, colorCode, formula}: OrdinariumProcessorProps){
  const [ordinarium, setOrdinarium] = useState([] as OrdinariumProps[]);
  useEffect(() => {
    axios.get("/api/ordinarium").then(res => {
      setOrdinarium(res.data);
    });
  }, []);
  if(ordinarium.length === 0) return <h2>Wczytuję...</h2>;

  const parts = ordinarium.filter(el => [colorCode, "*", formula].includes(el.color_code) && el.part === slugAndDePL(code.substring(1)));
  switch(code.substring(1)){
    case "Kyrie":
      return(
        <>
          <h2>Akt pokutny</h2>
          <Alternative>
            <div className="alt_group">
              <h4><strong>Kyrie</strong> zostaje</h4>
              <div className="alt_option">
                <p className="ksiadz">Spowiadam się Bogu Wszechmogącemu...</p>
              </div>
              <div className="alt_option">
                <Antiphon
                  call="Zmiłuj się nad nami, Panie"
                  resp="Bo zgrzeszyliśmy przeciw Tobie"
                />
                <Antiphon
                  call="Okaż nam, Panie, miłosierdzie swoje"
                  resp="I daj nam swoje zbawienie"
                />
              </div>
            </div>
            <div className="alt_group">
              <h4><strong>Kyrie</strong> pominięte</h4>
              <div className="alt_option">
                <Antiphon
                  call="Panie... ...Zmiłuj się nad nami"
                  resp="Zmiłuj się nad nami"
                />
              </div>
              <div className="alt_option">
                <i>Aspersja</i>
              </div>
            </div>
          </Alternative>
          <p className="ksiadz">Niech się zmiłuje nad nami Bóg Wszechmogący i, odpuściwszy nam grzechy, doprowadzi nas do życia wiecznego...</p>

          <h1>Kyrie</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div className="lyrics">
            <p>
              Panie, zmiłuj się nad nami<br />
              Chryste, zmiłuj się nad nami<br />
              Panie, zmiłuj się nad nami
            </p>
          </div>
        </>
      )
    case "Gloria":
      return(
        <>
          <h1>Gloria</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div className="lyrics">
            <p>
              Chwała na wysokości Bogu<br />
              A na ziemi pokój ludziom dobrej woli<br />
              Chwalimy Cię • Błogosławimy Cię<br />
              Wielbimy Cię • Wysławiamy Cię<br />
              Dzięki Ci składamy • Bo wielka jest chwała Twoja
            </p>
            <p>
              Panie Boże, królu nieba • Boże, Ojcze wszechmogący<br />
              Panie, Synu jednorodzony • Jezu Chryste<br />
              Panie Boże, Baranku Boży • Synu Ojca<br />
              Który gładzisz grzechy świata • Zmiłuj się nad nami<br />
              Który gładzisz grzechy świata • Przyjm błagania nasze<br />
              Który siedzisz po prawicy Ojca • Zmiłuj się nad nami
            </p>
            <p>
              Albowiem tylko Tyś jest święty • Tylko Tyś jest Panem<br />
              Tylko Tyś najwyższy • Jezu Chryste<br />
              Z Duchem Świętym, w chwale Boga Ojca, amen
            </p>
          </div>
        </>
      )
    case "Credo":
      return(
        <>
          <p className="ksiadz">Złóżmy wyznanie wiary:</p>
          <h1>Credo</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <table className="credo"><tbody>
            <tr><td>Wierzę w jednego Boga, Ojca wszechmogącego, Stworzyciela nieba i ziemi</td></tr>
            <tr><td>Wszystkich rzeczy widzialnych i niewidzialnych</td></tr>
            <tr><td>I w jednego Pana Jezusa Chrystusa, Syna bożego Jednorodzonego</td></tr>
            <tr><td>Który z Ojca jest zrodzony przed wszystkimi wiekami</td></tr>
            <tr><td>Bóg z Boga, światłość ze światłości</td></tr>
            <tr><td>Bóg prawdziwy z Boga prawdziwego</td></tr>
            <tr><td>Zrodzony a nie stworzony, współistotny Ojcu</td></tr>
            <tr><td>A przez niego wszystko się stało</td></tr>
            <tr><td>On to dla nas ludzi i dla naszego zbawienia</td></tr>
            <tr><td>Zstąpił z nieba</td></tr>
            <tr><td>I za sprawą Ducha świętego</td></tr>
            <tr><td>Przyjął ciało z Maryi Dziewicy i stał się człowiekiem</td></tr>
            <tr><td>Ukrzyżowany również za nas</td></tr>
            <tr><td>Pod Poncjuszem Piłatem został umęczony i pogrzebany</td></tr>
            <tr><td>I zmartwychwstał dnia trzeciego, jak oznajmia pismo</td></tr>
            <tr><td>I wstąpił do nieba, siedzi po prawicy Ojca</td></tr>
            <tr><td>I powtórnie przyjdzie w chwale sądzić żywych i umarłych</td></tr>
            <tr><td>A królestwu jego nie będzie końca</td></tr>
            <tr><td>Wierzę w Ducha Świętego, Pana i Ożywiciela</td></tr>
            <tr><td>Który od ojca i syna pochodzi</td></tr>
            <tr><td>Który z ojcem i synem wspólnie odbiera uwielbienie i chwałę</td></tr>
            <tr><td>Który mówił przez proroków</td></tr>
            <tr><td>Wierzę w jeden, święty, powszechny i apostolski kościół</td></tr>
            <tr><td>Wyznaję jeden chrzest na odpuszczenie grzechów</td></tr>
            <tr><td>I oczekuję wskrzeszenia umarłych</td></tr>
            <tr><td>I życia wiecznego w przyszłym świecie, amen</td></tr>
          </tbody></table>
        </>
      )
    case "Sanctus":
      return(
        <>
          <Antiphon
            call="Panie nasz, Boże..."
            resp="Amen"
          />
          <Antiphon
            call="Pan z wami"
            resp="I z duchem Twoim"
            respMelody={`K:Bb\nF2 EF (GF)F2 |]`}
          />
          <Antiphon
            call="W górę serca"
            resp="Wznosimy je do Pana"
            respMelody={`K:Eb\nG2 (FGA)G FG (FE)E2 |]`}
          />
          <Antiphon
            call="Dzięki składajmy Panu Bogu naszemu"
            resp="Godne to i sprawiedliwe"
            respMelody={`K:Eb\n(F2GA)GGG FG(FE)E2 |]`}
          />
          <p className="ksiadz">Zaprawdę godne to i sprawiedliwe... ...jednym głosem wołając:</p>

          <h1>Sanctus</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div className="lyrics">
            <p>
              Święty, Święty, Święty<br />
              Pan Bóg zastępów<br />
              Pełne są niebiosa<br />
              I ziema chwały Twojej<br />
              Hosanna na wysokości
            </p>
            <p>
              Błogosławiony<br />
              Który idzie w imię Pańskie<br />
              Hosanna na wysokości
            </p>
          </div>
        </>
      )
    case "PaterNoster":
      return(
        <>
          <p className="ksiadz">Nazywamy się dziećmi Bożymi i nimi jesteśmy, dlatego ośmielamy się mówić:</p>
          <h1>Pater Noster</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div className="lyrics">
            <p>
              Ojcze nasz, któryś jest w niebie<br />
              Święć się, imię Twoje<br />
              Przyjdź Królestwo Twoje<br />
              Bądź wola Twoja<br />
              Jako w niebie, tak i na ziemi
            </p>
            <p>
              Chleba naszego powszedniego daj nam dzisiaj<br />
              I odpuść nam nasze winy<br />
              Jako i my odpuszczamy naszem winowajcom<br />
              I nie wódź nas na pokuszenie<br />
              Ale nas zbaw ode złego
            </p>
          </div>
          <Antiphon
            call="Wybaw nas, Panie, od zła wszelkiego... ...naszego Zbawiciela, Jezusa Chrystusa"
            resp="Bo Twoje jest Królestwo, i potęga i chwała na wieki"
            respMelody={`K:Cm\n(CE) FF EFGF2 | FFFFF FEF E2C4 |]`}
          />
        </>
      )
    case "AgnusDei":
      return(
        <>
          <Antiphon
            call="Pokój Pański niech zawsze będzie z wami"
            resp="I z duchem Twoim"
          />
          <p className="ksiadz">Przekażcie sobie znak pokoju:</p>

          <h1>Agnus Dei</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div className="lyrics">
            <p>
              Baranku Boży<br />
              Który gładzisz grzechy świata<br />
              Zmiłuj się nad nami
            </p>
            <p>
              Baranku Boży<br />
              Który gładzisz grzechy świata<br />
              Zmiłuj się nad nami
            </p>
            <p>
              Baranku Boży<br />
              Który gładzisz grzechy świata<br />
              Obdarz nas pokojem
            </p>
          </div>
        </>
      )
    default:
      return(
        <>
          <h2>🚧{code}</h2>
        </>
      )
  }
}

export function ExtrasProcessor({elem}: {elem: MassElem}){
  let songs_to_add: string[];

  switch(elem.code.substring(1)){
    case "Greetings":
      return(
        <>
          <Antiphon
            call="W imię Ojca i Syna i Ducha Świętego"
            resp="Amen"
          />
          <Antiphon
            call="Pan z wami"
            resp="I z duchem Twoim"
          />
        </>
      )
    case "LUP1": //Let us pray
    case "LUP2": //Let us pray
      return(
        <>
          <Antiphon
            call="Módlmy się..."
            resp="Amen"
          />
        </>
      )
    case "Evang":
      return(
        <>
          <Antiphon
            call="Pan z wami"
            resp="I z duchem Twoim"
          />
          <Antiphon
            call="Słowa Ewangelii według świętego X"
            resp="Chwała Tobie, Panie"
            respMelody={`FF DF F2F4 |]\nw: Chwa-ła To-bie, Pa-nie`}
          />
          <h1>Ewangelia</h1>
          <Antiphon
            call="Oto Słowo Pańskie"
            resp="Chwała Tobie, Chryste"
            respMelody={`FF DF F2F4 |]\nw: Chwa-ła To-bie, Chry-ste`}
          />
        </>
      )
    case "Passion":
      return(
        <>
          <p className="ksiadz">Męka naszego Pana... ...według świętego X</p>
          <h1>Pasja</h1>
          <Antiphon
            call="Oto Słowo Pańskie"
            resp="Chwała Tobie, Chryste"
          />
        </>
      )
    case "GI": // General Intercessions
      return(
        <>
          <h2>Modlitwa powszechna</h2>
          <Antiphon
            call="Ciebie prosimy"
            resp="Wysłuchaj nas, Panie"
            respMelody={`K:F\nc2 cBA (GB) A4 |]\nw: Wy-słu-chaj nas, Pa_nie`}
          />
          <Antiphon
            call="Módlmy się... ...przez wszystkie wieki wieków"
            resp="Amen"
          />
        </>
      )
    case "GIFriday":
      return <>
        <h2>Uroczysta modlitwa powszechna</h2>
        <Antiphon
          call="Wszechmogący, wieczny Boże... ...przez Chrystusa, Pana naszego"
          resp="Amen"
        />
      </>
    case "Wedding":
      songs_to_add = [
        "O Stworzycielu Duchu",
      ]

      return(
        <>
          <h2>Przysięga ślubna</h2>
          <p className="ksiadz">Prośmy więc Ducha Świętego... ...Chrystusa i Kościoła:</p>
          <div className="songMeta">
            <h1>{songs_to_add[0]}</h1>
            <SongRender title={songs_to_add[0]} />
          </div>
        </>
      )
    case "Transf":
      return(
        <>
          <h1>Przemienienie</h1>
          <Alternative>
            <div className="alt_option">
              <Antiphon
              call="Oto wielka tajemnica wiary"
              resp="Głosimy śmierć Twoją, Panie Jezu, <br />wyznajemy Twoje zmartwychwstanie <br />i oczekujemy Twego przyjścia w chwale"
              respMelody={`K:Em\nE8 FGF2E2 | E8 FG (AB)B2 | A8 GFE2E2 |]`}
              />
            </div>
            <div className="alt_option">
              <Antiphon
              call="Tajemnica wiary"
              resp="Chrystus umarł, <br />Chrystus zmartwychwstał, <br />Chrystus powróci"
              respMelody={`K:Gm\nG(GF) GG2 | AGF GG2 | GGF GD2 |]`}
              />
            </div>
            <div className="alt_option">
              <Antiphon
              call="Wielka jest tajemnica naszej wiary"
              resp="Ile razy ten chleb spożywamy <br />i pijemy z tego kielicha, <br />głosimy śmierć Twoją, Panie, <br />oczekując Twego przyjścia w chwale"
              respMelody={`K:F\nF8 GA(GF)F2 | D8 DCD FF2 | A8 AGF GG2 | F8 GAGF (DF)F2 |]`}
              />
            </div>
            <div className="alt_option">
              <Antiphon
              call="Uwielbiajmy tajemnicę wiary"
              resp="Panie, Ty nas wybawiłeś <br />przez krzyż i zmartwychwstanie swoje, <br />Ty jesteś zbawicielem świata"
              respMelody={`K:C\nCC DC EF (GA)G2 | A _BAGF GAGG2 | GGG AGFD (ED)C2 |]`}
              />
            </div>
          </Alternative>
          <Antiphon
            call="Przez Chrystusa, z Chrystusem i w Chrystusie... ...przez wszystkie wieki wieków"
            resp="Amen"
          />
        </>
      )
    case "Blessing":
    case "EasterBlessing":
      const easter_flag = elem.code.includes("Easter");
      const easter_add = (easter_flag) ? ", alleluja, alleluja" : "";
      return(
          <>
            <h1>Błogosławieństwo</h1>
            <Antiphon
              call="Pan z wami"
              resp="I z duchem Twoim"
            />
            <Antiphon
              call="Niech was błogosławi Bóg Wszechmogący, Ojciec i Syn i Duch Święty"
              resp="Amen"
            />
            <Antiphon
              call={`Idźcie w pokoju Chrystusa${easter_add}`}
              resp={`Bogu niech będą dzięki${easter_add}`}
              respMelody={easter_flag
                ? `K:Bb\nF8 (FG)G2 | FE (FG)G2 | G(F BAGF) (EFG) (G2F4) |]`
                : `K:Dm\nDFG (AG)F E2D4 |]`
              }
            />
          </>
        )
    case "Lorette":
      songs_to_add = [
        "Litania Loretańska",
        "Pod Twoją obronę",
      ]

      return(
        <>
          <div className="songMeta">
            <h1>{songs_to_add[0]}</h1>
            <SongRender title={songs_to_add[0]} />
          </div>
          <hr />
          <Alternative>
            <div className="alt_option">
              <Antiphon
                call="Módl się za nami, święta Boża rodzicielko"
                resp="Abyśmy się stali godnymi obietnic chrystusowych"
              />
            </div>
            <div className="alt_option">
              <Antiphon
                call="Raduj się i wesel, Panno Maryjo, Alleluja"
                resp="Bo zmartwychwstał prawdziwie, Alleluja"
              />
            </div>
          </Alternative>
          <p>Módlmy się: Panie nasz, Boże, dozwól nam, sługom swoim, cieszyć się trwałym zdrowiem duszy i ciała. I za wstawiennictwem Najświętszej Maryi zawsze dziewicy, uwolnij nas od doczesnych utrapień i obdarz wieczną radością, przez Chrystusa, Pana naszego...</p>
          <hr />
          <div className="songMeta">
            <h1>{songs_to_add[1]}</h1>
            <SongRender title={songs_to_add[1]} />
          </div>
        </>
      )
    case "Heart":
      songs_to_add = [
        "Litania do Serca Jezusowego",
        "Do Serca Twojego",
      ]

      return(
        <>
          <div className="songMeta">
            <h1>{songs_to_add[0]}</h1>
            <SongRender title={songs_to_add[0]} />
          </div>
          <hr />
          <Antiphon
            call="Jezu cichy i pokornego serca"
            resp="Uczyń serca nasze według serca Twego"
          />
          <p>Módlmy się: wszechmogący, wieczny Boże, wejrzyj na Serce najmilszego Syna swego i na chwałę, i zadość uczynienie, jakie w imieniu grzeszników ci składa; daj się przebłagać tym, którzy żebrzą Twego miłosierdzia i racz udzielić przebaczenia w imię tegoż Syna swego, Jezusa Chrystusa, który z tobą żyje i króluje na wieki wieków...</p>
          <hr />
          <div className="songMeta">
            <h1>{songs_to_add[1]}</h1>
            <SongRender title={songs_to_add[1]} />
          </div>
        </>
      )
    case "LitanyBlood":
      songs_to_add = [
        "Litania do Najdroższej Krwi Pana Jezusa",
      ]

      return(
        <>
          <div className="songMeta">
            <h1>{songs_to_add[0]}</h1>
          </div>
          <hr />
          <Antiphon
            call="Odkupiłeś nas, Panie, Krwią swoją"
            resp="I uczyniłeś nas królestwem Boga naszego"
          />
          <p>Módlmy się: Wszechmogący, wieczny Boże, Ty Jednorodzonego Syna swego ustanowiłeś Odkupicielem świata i Krwią Jego dałeś się przebłagać, daj nam, prosimy, godnie czcić zapłatę naszego zbawienia i dzięki niej doznawać obrony od zła doczesnego na ziemi, abyśmy wiekuistym szczęściem radowali się w niebie. Przez Chrystusa, Pana naszego...</p>
        </>
      )
    case "Michael":
      return(
        <>
          <h1>Modlitwa do Michała Archanioła</h1>
          <div className="flex-right center wrap">
            <DummyInput label="Numer w śpiewniku Preis" value={601} />
          </div>
          <p>
            Święty Michale, Archaniele, wspomagaj nas w walce,
            a przeciw niegodziwości i zasadzkom złego ducha bądź nam obroną.
            Oby go Bóg pogromić raczył, pokornie o to prosimy.
            A Ty, wodzu niebieskich zastępów, szatana i inne duchy złe,
            które na zgubę dusz ludzkich po tym świecie krążą,
            mocą Bożą strąć do piekła. Amen.
          </p>
        </>
      )
    case "Exposition":
      songs_to_add = [
        "O zbawcza Hostio",
        "Przed tak wielkim Sakramentem"
      ]

      return(
        <>
          <h1>Wystawienie Najświętszego Sakramentu</h1>
          <div className="songMeta">
            <h2>Okadzenie</h2>
            <h1>{songs_to_add[0]}</h1>
            <SongRender title={songs_to_add[0]} />
          </div>
          <hr />
          <h2>Modlitwy</h2>
          <hr />
          <div className="songMeta">
            <h2>Przed błogosławieństwem</h2>
            <h1>{songs_to_add[1]}</h1>
          </div>
          <SongRender title={songs_to_add[1]} />
          <hr />
          <Antiphon
            call="Módlmy się..."
            resp="Amen"
            />
          <h2>Błogosławieństwo monstrancją</h2>
        </>
      )
    case "Lamentations":
      const parts = [
        "Pobudka",
        "Intencja",
        "Hymn",
        "Lament duszy nad cierpiącym Jezusem",
        "Rozmowa duszy z Matką bolesną",
      ]
      const winddown = "Któryś za nas cierpiał rany"
      const intentions = [
        `Przy pomocy łaski Bożej przystępujemy do rozważania męki Pana naszego Jezusa Chrystusa. Ofiarować ją będziemy Ojcu niebieskiemu na cześć i chwałę Jego Boskiego Majestatu, pokornie Mu dziękując za wielką i niepojętą miłość ku rodzajowi ludzkiemu, iż raczył zesłać Syna swego, aby za nas wycierpiał okrutne męki i śmierć podjął krzyżową. To rozmyślanie ofiarujemy również ku czci Najświętszej Maryi Panny, Matki Bolesnej, oraz ku uczczeniu Świętych Pańskich, którzy wyróżniali się nabożeństwem ku Męce Chrystusowej.\n
        W pierwszej części będziemy rozważali, co Pan Jezus wycierpiał od modlitwy w Ogrójcu aż do niesłusznego przed sądem oskarżenia. Te zniewagi i zelżywości temuż Panu, za nas bolejącemu, ofiarujemy za Kościół święty katolicki, za najwyższego Pasterza z całym duchowieństwem, nadto za nieprzyjaciół krzyża Chrystusowego i wszystkich niewiernych, aby im Pan Bóg dał łaskę nawrócenia i opamiętania.`,
        `W drugiej części rozmyślania męki Pańskiej będziemy rozważali, co Pan Jezus wycierpiał od niesłusznego przed sądem oskarżenia aż do okrutnego cierniem ukoronowania. Te zaś rany, zniewagi i zelżywości temuż Jezusowi cierpiącemu ofiarujemy, prosząc Go o pomyślność dla Ojczyzny naszej, o pokój i zgodę dla wszystkich narodów, a dla siebie o odpuszczenie grzechów, oddalenie klęsk i nieszczęść doczesnych, a szczególnie zarazy, głodu, ognia i wojny.`,
        `W tej ostatniej części będziemy rozważali, co Pan Jezus wycierpiał od chwili ukoronowania aż do ciężkiego skonania na krzyżu. Te bluźnierstwa, zelżywości i zniewagi, jakie Mu wyrządzano, ofiarujemy za grzeszników zatwardziałych, aby Zbawiciel pobudził ich serca zbłąkane do pokuty i prawdziwej życia poprawy oraz za dusze w czyśćcu cierpiące, aby im litościwy Jezus krwią swoją świętą ogień zagasił; prosimy nadto, by i nam wyjednał na godzinę śmierci skruchę za grzechy i szczęśliwe w łasce Bożej wytrwanie.`,
      ]
      const [variant, setVariant] = useState(0)
      const changeVariant = (new_variant: number) => setVariant(new_variant)

      return(
        <>
          <h1>Gorzkie żale</h1>

          <div className="flex-right center">
          {[0, 1, 2].map(var_no =>
            <Button key={var_no}
              className={[variant === var_no && 'accent-border'].filter(Boolean).join(" ")}
              onClick={() => changeVariant(var_no)}>
              {var_no + 1}
            </Button>)}
          </div>

          {parts.map(part =>
            <div key={part} className="songMeta">
              <h2>{part}</h2>
              {part === "Intencja"
                ? <div>{intentions[variant].split("\n").map((p, i) => <p key={i}>{p}</p>)}</div>
                : <SongRender title={`Gorzkie żale: ${part}`} forceLyricsVariant={variant} />}
            </div>)}

          <div className="songMeta">
            <h2>{winddown}</h2>
            <SongRender title={winddown} />
          </div>
        </>
      )
    case "CrossAdoration":
      return <>
        <h2>Ukazanie Krzyża</h2>
        <Antiphon
          call="Oto drzewo krzyża, na którym zawisło zbawienie świata"
          resp="Pójdźmy z pokłonem"
          respMelody={`K:F\nc2 AB (AG)F2 |]`}
        />
        <h2>Adoracja Krzyża</h2>
        <span className="ghost">Przy <i>Ludu, mój ludu</i> po każdej zwrotce dodatkowo 2× <i>Święty Boże</i></span>
      </>
    case "Light":
      return <>
        <Antiphon
          call="Światło Chrystusa"
          resp="Bogu niech będą dzięki"
        />
      </>
    case "MonstranceBlessing":
      return <h2>Błogosławieństwo monstrancją</h2>
    case "EasterResp":
      return <>
        <Antiphon
          call="Niebo i ziemia się cieszą, alleluja"
          resp="Ze zmartwychwstania Twojego, Chryste, alleluja"
        />
        <Antiphon
          call="Módlmy się..."
          resp="Amen"
        />
      </>
    default:{
      return(
        <>
          <h2>{elem.label}</h2>
        </>
      )
    }
  }
}
