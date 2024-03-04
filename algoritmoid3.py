import pandas as pd
import numpy as np

# Creamos un conjunto de datos de ejemplo
data = pd.DataFrame({
    'País': ['Francia', 'Alemania', 'Brasil', 'Japón', 'Sudáfrica', 'Australia', 'Argentina', 'Chile', 'Colombia', 'Perú', 'Ecuador', 'Uruguay'],
    'Continente': ['Europa', 'Europa', 'América del Sur', 'Asia', 'África', 'Oceanía', 'América del Sur', 'América del Sur', 'América del Sur', 'América del Sur', 'América del Sur', 'América del Sur'],
    'Color_Bandera': ['Tricolor', 'Tricolor', 'Verde y Amarillo', 'Rojo y Blanco', 'Negro, Verde, Amarillo', 'Azul', 'Celeste y Blanco', 'Rojo, Blanco, Azul', 'Amarillo, Azul, Rojo', 'Rojo, Blanco', 'Amarillo, Azul, Rojo', 'Blanco y Azul'],
    'Es_Capital': [1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1]  # 1 si es capital, 0 si no lo es
})

# Definimos la función para calcular la entropía
def entropia(y):
    p = np.unique(y, return_counts=True)[1] / y.size
    return -np.sum(p * np.log2(p))

# Definimos la función para calcular la ganancia de información
def ganancia_informacion(y, x):
    y_entropy = entropia(y)
    x_entropy = np.array([entropia(y[x == i]) for i in np.unique(x)])
    p = np.array([np.sum(x == i) / y.size for i in np.unique(x)])
    return y_entropy - np.sum(p * x_entropy)

# Función para hacer preguntas y adivinar el país
def adivinar_pais(respuestas):
    if len(respuestas) == len(preguntas):
        # Convertimos las respuestas a un DataFrame temporal
        temp_df = pd.DataFrame([respuestas])

        # Calculamos la ganancia de información para cada pregunta
        ganancias = {columna: ganancia_informacion(data['País'].values, temp_df[columna].values) for columna in temp_df.columns}

        # Elegimos la pregunta con la mayor ganancia de información
        mejor_pregunta = max(ganancias, key=ganancias.get)

        # Mostramos la mejor pregunta y adivinamos el país
        respuesta_adivinanza = data[data[mejor_pregunta] == temp_df[mejor_pregunta].iloc[0]]['País'].mode().values
        print(f'Mejor pregunta: {pregunta_textos[mejor_pregunta]}\n\n¡Adivino que el país es {respuesta_adivinanza[0]}!')
    else:
        pregunta_actual = list(preguntas.keys())[len(respuestas)]
        respuesta = input(f'{pregunta_textos[pregunta_actual]}: ')
        respuestas[pregunta_actual] = respuesta
        adivinar_pais(respuestas)

# Preguntas sobre los países
preguntas = {
    'Continente': '¿En qué continente se encuentra el país? (Europa, América del Sur, Asia, África, Oceanía)',
    'Color_Bandera': '¿Qué colores tiene la bandera del país? (Escribe los colores separados por comas)',
    'Es_Capital': '¿Es la capital del país? (Sí o No)'
}

pregunta_textos = {
    'Continente': 'En qué continente se encuentra el país',
    'Color_Bandera': 'Qué colores tiene la bandera del país',
    'Es_Capital': 'Es la capital del país'
}

# Llamamos a la función para adivinar el país
adivinar_pais({})
