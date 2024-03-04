from flask import Flask, render_template, request
import mysql.connector
import random

app = Flask(__name__)

def conectar_base_de_datos():
    try:
        conexion = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='happy'
        )
        return conexion
    except mysql.connector.Error as err:
        print(f"Error al conectar a la base de datos: {err}")
        return None

def obtener_precio_producto(nombre_producto, conexion):
    try:
        cursor = conexion.cursor()
        consulta = f"SELECT precio FROM productos WHERE nombre = '{nombre_producto}'"
        cursor.execute(consulta)
        resultado = cursor.fetchone()
        cursor.close()
        return resultado[0] if resultado else None
    except mysql.connector.Error as err:
        print(f"Error al obtener el precio del producto: {err}")
        return None

def obtener_respuesta_precio(nombre_producto, conexion):
    precio = obtener_precio_producto(nombre_producto, conexion)

    if precio is not None:
        respuestas = [
            f"Asistente: El precio de {nombre_producto} es ${precio}.",
            f"Asistente: El costo de {nombre_producto} asciende a ${precio}.",
            f"Asistente: Actualmente, {nombre_producto} tiene un precio de ${precio}."
        ]
        return random.choice(respuestas)
    else:
        return f"Asistente: Lo siento, no encontré información sobre el precio de {nombre_producto}."

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/procesar_pregunta', methods=['POST'])
def procesar_pregunta():
    pregunta_original = request.form['pregunta'].lower()
    conexion_db = conectar_base_de_datos()

    if pregunta_original == 'salir':
        return "Hasta luego."
    elif 'precio' in pregunta_original:
        palabras = pregunta_original.split()
        indice_precio = palabras.index('precio')

        if indice_precio + 1 < len(palabras):
            nombre_producto = palabras[indice_precio + 1]
            respuesta_precio = obtener_respuesta_precio(nombre_producto, conexion_db)
            return f"Asistente: Respuesta del chatbot para: {pregunta_original}\n{respuesta_precio}"
        else:
            return "Asistente: Por favor, proporciona el nombre del producto después de 'precio'."
    elif 'gracias' in pregunta_original:
        return "Asistente: De nada."
    elif 'ok' in pregunta_original:
        return "Asistente: Estoy para ayudar."
    else:
        return f"Asistente: No entiendo la pregunta. Pregunta sobre precios o expresa tu agradecimiento para recibir información."

if __name__ == '__main__':
    app.run(debug=True)
