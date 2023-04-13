import React from "react";
import style from "./style.module.css";
import { Section } from "../../components/Section";

declare interface ErrorPageProps{
  code: number,
  desc: string,
}

export function ErrorPage({code, desc}: ErrorPageProps){
  return(
    <Section title={desc}>
      <div className="flex-down center">
        <h1 className={style.errorCode}>{code}</h1>
        <p>{desc}</p>
      </div>
    </Section>
  )
}
