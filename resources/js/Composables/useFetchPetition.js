/**
 * Composable para realizar peticiones HTTP estandarizadas usando fetch()
 * No lanza alertas ni loaders — sólo retorna la respuesta del servidor.
 *
 * @returns {function} fetchPetition(url, options)
 */
export function useFetchPetition() {
    /**
     * Ejecuta una petición fetch con manejo básico de JSON y errores.
     *
     * @param {string} url - Endpoint del backend (ej: '/api/yards')
     * @param {object} options - Configuración: { method, body, headers }
     * @returns {Promise<{ data: any, ok: boolean, status: number|null, error: Error|null }>}
     */
    const fetchPetition = async (url, options = {}) => {
        const {
            method = 'GET',
            body = null,
            headers = {},
        } = options;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const defaultHeaders = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        };

        try {
            const response = await fetch(url, {
                method,
                headers: { ...defaultHeaders, ...headers },
                body: body ? JSON.stringify(body) : null,
                credentials: 'same-origin', //Enviar cookies de sesión Fortify
            });

            const status = response.status;
            const ok = response.ok;
            let data = null;

            try {
                data = await response.json();
            } catch {
                data = null; // si no es JSON (por ejemplo, DELETE 204)
            }

            return { data, ok, status, error: null };
        } catch (error) {
            console.error('❌ Fetch Error:', error);
            return { data: null, ok: false, status: null, error };
        }
    };

    return { fetchPetition };
}