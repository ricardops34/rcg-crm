export interface AuthProgram {
  id?: number;
  name?: string;
  label?: string;
  controller: string;
  icon?: string;
  action?: string;
}

export interface AuthModuleMenu {
  label: string;
  icon?: string;
  subItems: Array<{
    label: string;
    icon?: string;
    action: string;
  }>;
}

export interface AuthTerms {
  text: string;
  version: string;
}

export interface AuthUser {
  id?: number;
  login: string;
  name?: string;
  email?: string;
  avatar?: string;
  unit?: {
    id?: number;
    name?: string;
    logo?: string;
    favicon?: string;
  };
  allowedUnits?: Array<{
    id: number;
    name: string;
    logo?: string;
    favicon?: string;
  }>;
  roles?: string[];
  isGerente?: boolean;
  supervisorId?: number;
  programs?: AuthProgram[];
  frontpage?: {
    controller?: string;
  };
}

export interface AuthResponse {
  accessToken?: string;
  user?: AuthUser;
  nextStep?: '2FA' | 'TERMS';
}

export interface LoginPayload {
  login: string;
  password: string;
  systemUnitId?: number;
}

export interface LoginUnitOption {
  value: number;
  label: string;
  logo?: string;
  favicon?: string;
}

export interface SaveTermsPayload {
  text: string;
  version: string;
}

export interface JwtPayload {
  exp?: number;
  scope?: string;
}
