// import style from "./style.module.css"
import { MassElem, MassElemSectionProps, OrdinariumProcessorProps, OrdinariumProps } from "../types"
import { ReactNode, useContext, useState, useEffect } from "react";
import { slugAndDePL } from "../helpers";
import { Button } from "./Interactives";
import { MModContext } from "../pages/Set";
import { SheetMusicRender } from "./SheetMusicRender";
import axios from "axios";

export function MassElemSection({id, uneresable = false, children}: MassElemSectionProps){
  const MMod = useContext(MModContext);

  return(
    <section id={id} className="massElemSection">
    {!uneresable &&
      <div className="massElemEraser flex-right"
        onMouseOver={() => MMod.prepareMassElemErase(id)}
        onMouseOut={() => MMod.prepareMassElemErase("!"+id)}
        >
        <Button onClick={() => MMod.eraseMassElem(id)}>&#10003;</Button>
        <Button>–</Button>
      </div>}
      {children}
    </section>
  )
}

export function SongLyrics({lyrics}: {lyrics: string | null}){
  const lyrics_processed = lyrics?.replace(/\*\*\s*\r?\n/g, '</span><br>')
    .replace(/\*\s*\r?\n/g, `<span class="$"chorus"">`)
    .replace(/_(.{1,5})_/g, '<u>$1</u>')
    .replace(/\d+\.\s*\r?\n/g, match => {return "<li start="+match.substring(0, match.length - 2)+">"})
    .replace(/\r?\n/g, "<br />");

  return(<ol className="lyrics" dangerouslySetInnerHTML={{ __html: lyrics_processed ?? ""}} />);
}

export function PsalmLyrics({lyrics}: {lyrics: string}){
  return(
    <div className="psalm">
    {lyrics.split(/\r?\n\r?\n/).map((out, i) =>
      <p key={i} dangerouslySetInnerHTML={{ __html: out.replace(/\r?\n/g, "<br>")}} />
    )}
    </div>
  )
}

export function Antiphon({call, resp}: {call: string, resp: string}){
  return(
    <div className="flex-right center antyfona">
      <div>{call}</div>
      <div>→</div>
      <div dangerouslySetInnerHTML={{ __html : resp}}></div>
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

export function OrdinariumProcessor({code, colorCode}: OrdinariumProcessorProps){
  const [ordinarium, setOrdinarium] = useState([] as OrdinariumProps[]);
  useEffect(() => {
    axios.get("/api/ordinarium").then(res => {
      setOrdinarium(res.data);
    });
  }, []);
  if(ordinarium.length === 0) return <h2>Wczytuję...</h2>;

  const parts = ordinarium.filter(el => (el.color_code === colorCode || el.color_code === "*") && el.part === slugAndDePL(code.substring(1)));
  switch(code.substring(1)){
    case "Kyrie":
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
          <div>
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
          <div>
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
          />
          <Antiphon
            call="W górę serca"
            resp="Wznosimy je do Pana"
          />
          <Antiphon
            call="Dzięki składajmy Panu Bogu naszemu"
            resp="Godne to i sprawiedliwe"
          />
          <p className="ksiadz">Zaprawdę godne to i sprawiedliwe... ...jednym głosem wołając:</p>

          <h1>Sanctus</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div>
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
          <Antiphon
            call="Przez Chrystusa, z Chrystusem i w Chrystusie... ...przez wszystkie wieki wieków"
            resp="Amen"
          />
          <p className="ksiadz">Nazywamy się dziećmi Bożymi i nimi jesteśmy, dlatego ośmielamy się mówić:</p>
          <h1>Pater Noster</h1>
          {parts.map((part, i) => <SheetMusicRender notes={part.sheet_music} key={i} />)}
          <div>
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
          <div>
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
  switch(elem.code.substring(1)){
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
          />
          <h1>Ewangelia</h1>
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
          />
          <Antiphon
            call="Módlmy się... ...przez wszystkie wieki wieków"
            resp="Amen"
          />
        </>
      )
    case "Wedding":
      return(
        <>
          <h1>Przysięga ślubna</h1>
          <p className="ksiadz">Prośmy więc Ducha Świętego... ...Chrystusa i Kościoła:</p>
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
              />
            </div>
            <div className="alt_option">
              <Antiphon
              call="Tajemnica wiary"
              resp="Chrystus umarł, <br />Chrystus zmartwychwstał, <br />Chrystus powróci"
              />
            </div>
            <div className="alt_option">
              <Antiphon
              call="Wielka jest tajemnica naszej wiary"
              resp="Ile razy ten chleb spożywamy <br />i pijemy z tego kielicha, <br />głosimy śmierć Twoją, Panie, <br />oczekując Twego przyjścia w chwale"
              />
            </div>
            <div className="alt_option">
              <Antiphon
              call="Uwielbiajmy tajemnicę wiary"
              resp="Panie, Ty nas wybawiłeś <br />przez krzyż i zmartwychwstanie swoje, <br />Ty jesteś zbawicielem świata"
              />
            </div>
          </Alternative>
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
            />
          </>
        )
    default:{
      return(
        <>
          <h1>{elem.label}</h1>
        </>
      )
    }
  }
}
