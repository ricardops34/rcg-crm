const getApiUrl = (): string => {
  if (typeof window !== 'undefined') {
    const host = window.location.hostname;
    // Se estiver rodando localmente (localhost, 127.0.0.1 ou IPs de rede privada local)
    if (
      host === 'localhost' || 
      host === '127.0.0.1' || 
      host.startsWith('192.168.') || 
      host.startsWith('10.') || 
      host.startsWith('172.')
    ) {
      return `http://${host}:3000`;
    }
  }
  return 'https://crmapi.bjsoft.com.br';
};

export const environment = {
  production: true,
  apiUrl: getApiUrl()
};

