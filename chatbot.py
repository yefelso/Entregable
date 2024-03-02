import tkinter as tk
from tkinter import scrolledtext
import mysql.connector
import re
import random

class ChatbotApp:
    def __init__(self, root):
        self.root = root
        self.root.title("ChatBot Tkinter")
        self.root.geometry("400x400")

        # Configura la conexión a la base de datos MySQL
        self.db_connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='happy'
        )

        self.create_widgets()

    def create_widgets(self):
        self.chat_display = scrolledtext.ScrolledText(self.root, wrap=tk.WORD, width=40, height=15)
        self.chat_display.pack(padx=10, pady=10)

        self.input_entry = tk.Entry(self.root, width=40)
        self.input_entry.pack(padx=10, pady=5)

        self.send_button = tk.Button(self.root, text="Enviar", command=self.send_message)
        self.send_button.pack(pady=10)

    def send_message(self):
        user_input = self.input_entry.get()
        bot_response = get_response(user_input)

        # Mostrar mensajes en la interfaz gráfica
        self.chat_display.insert(tk.END, f"You: {user_input}\n")
        self.chat_display.insert(tk.END, f"Bot: {bot_response}\n")
        self.chat_display.yview(tk.END)

        # Limpiar la entrada
        self.input_entry.delete(0, tk.END)

        # Insertar la conversación en la base de datos
        self.insert_conversation("usuario", user_input, bot_response)

    def insert_conversation(self, usuario, mensaje_usuario, mensaje_bot):
        cursor = self.db_connection.cursor()
        insert_query = "INSERT INTO conversaciones (usuario, mensaje_usuario, mensaje_bot) VALUES (%s, %s, %s)"
        data = (usuario, mensaje_usuario, mensaje_bot)
        cursor.execute(insert_query, data)
        self.db_connection.commit()
        cursor.close()

def get_response(user_input):
    split_message = re.split(r'\s|[,:;.?!-_]\s*', user_input.lower())
    return check_all_messages(split_message)

def message_probability(user_message, recognized_words, single_response=False, required_word=[]):
    message_certainty = 0
    has_required_words = True

    for word in user_message:
        if word in recognized_words:
            message_certainty += 1

    percentage = float(message_certainty) / float(len(recognized_words))

    for word in required_word:
        if word not in user_message:
            has_required_words = False
            break

    if has_required_words or single_response:
        return int(percentage * 100)
    else:
        return 0

def check_all_messages(message):
    highest_prob = {}

    def response(bot_response, list_of_words, single_response=False, required_words=[]):
        nonlocal highest_prob
        highest_prob[bot_response] = message_probability(message, list_of_words, single_response, required_words)

    response('Hola', ['hola', 'klk', 'saludos', 'buenas'], single_response=True)
    response('Estoy bien y tú?', ['como', 'estas', 'va', 'vas', 'sientes'], required_words=['como'])
    response('Estamos ubicados en la calle 23 numero 123', ['ubicados', 'direccion', 'donde', 'ubicacion'], single_response=True)
    response('Siempre a la orden', ['gracias', 'te lo agradezco', 'thanks'], single_response=True)
    response('que tal', ['hola', 'klk', 'saludos', 'buenas'], single_response=True)

    best_match = max(highest_prob, key=highest_prob.get)
    return unknown() if highest_prob[best_match] < 1 else best_match

def unknown():
    response = ['puedes decirlo de nuevo?', 'No estoy seguro de lo que quieres', 'búscalo en Google a ver qué tal'][random.randrange(3)]
    return response

if __name__ == "__main__":
    root = tk.Tk()
    app = ChatbotApp(root)
    root.mainloop()
