// frontend/app/composables/useApiUrl.ts

export const useApiUrl = () => {
  const config = useRuntimeConfig()
  
  // Get apiBase and strip trailing slashes if any
  const rawBase = config.public.apiBase || 'http://localhost:8000'
  const apiBase = rawBase.replace(/\/+$/, '')
  
  return {
    apiBase,
    apiUrl: `${apiBase}/api`,
    storageUrl: `${apiBase}/storage`
  }
}
