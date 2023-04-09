export interface SongProps{
  title: string,
  categoryCode: number,
  categoryDesc: string | null,
  numberPreis: number | string | null,
  key?: string,
  preferences: string,
  lyrics: string | null,
  sheetMusic: string | null,
};

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
}

export interface MassElem{
  code: string,
  label: string,
  content: string,
}

export type InputProps = {
  name: string,
  label: string,
} & ({
  type?: "TEXT";
  value?: string | number | null,
  onChange?: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
} | {
  type: "checkbox",
  value?: boolean,
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
} | {
  type?: "text",
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
}