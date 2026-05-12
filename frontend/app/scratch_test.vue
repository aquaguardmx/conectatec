
import { useProductos } from './composables/useProductos'

export default defineComponent({
  async setup() {
    const { obtenerTodosProductos } = useProductos()
    const response = await obtenerTodosProductos()
    console.log('Response:', JSON.stringify(response, null, 2))
  }
})
