import { ReactNode } from "react";

export type CheckAuthProps = (pass: string) => boolean

export interface AuthProps{
    auth: boolean,
    checkAuth: CheckAuthProps,
}

export interface DataChangeProps{
    (
        fileToChange: string,
        itemToChange: SongProps | OrdinariumProps | Formula,
        item_id?: number,
    ): void;
}

export interface MModProps{
  prepareMassElemErase: (id: string) => void,
  eraseMassElem: (id: string) => void,
}

export interface SongProps{
  title: string,
  categoryCode: number,
  categoryDesc: string | null,
  numberPreis: number | string | null,
  key?: string,
  preferences: string,
  lyrics: string | null,
  sheetMusic: string | null,
}

export interface OrdinariumProps{
  colorCode: string,
  part: string,
  sheetMusic: string,
}

export interface OrdinariumColorProps{
  name: string,
  displayColor?: string,
  displayName: string,
  desc: string,
}

export interface Extra{
  songName: string,
  preWhere?: string,
  replace: boolean,
}

export interface Formula{
  name: string,
  gloriaPresent: boolean,
  extra?: Extra[],
}

export interface Set{
  name: string,
  createdAt: string,
  formulaName: string,
  color: string,
  sPre?: string,
  sIntro?: string,
  pPsalm?: string,
  pAccl?: string,
  sOffer?: string,
  sCommunion?: string,
  sAdoration?: string,
  sDismissal?: string,
  extra?: Extra[],
  thisMassOrder?: MassElem[],
}

export interface MassElem{
  code: string,
  label: string,
  content?: string,
}

export interface MassElemSectionProps{
  id: string,
  uneresable?: boolean,
  children: ReactNode,
}

export interface OrdinariumProcessorProps{
  code: string,
  colorCode: string,
}

export type InputProps = {
  name: string,
  label: string,
  disabled?: boolean,
} & ({
  type?: "TEXT";
  value?: string | number | null,
  onChange?: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
} | {
  type: "checkbox",
  value?: boolean,
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
} | {
  type?: "text" | "password",
  value?: string | number | null,
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
})

export type SelectProps = {
  name: string,
  label: string,
  value?: string | number,
  firstEmpty?: boolean,
  options?: SelectOption[],
  onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void;
}

export interface PreferencesProps{
  preferences: string,
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

export interface SelectOption{
  value: number | string,
  label: string,
  key?: number,
}
