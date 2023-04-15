import { Section } from "../components/Section";

declare interface ErrorPageProps{
  code: number,
  desc: string,
}

export function ErrorPage({code, desc}: ErrorPageProps){
  return(
    <Section title={desc}>
      <div className="flex-down center">
        <h1 className="errorCode">{code}</h1>
        <p>{desc}</p>
      </div>
    </Section>
  )
}
