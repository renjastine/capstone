import face_recognition
import os

class FaceRecog:
    def __init__(self, k, u):
        self.k = k
        self.u = u
    
    # get images
    def get_images(self, knownPath, unknownPath):
        listKnownNames = self.k
        listUnknownNames = self.u

        k_pics = []
        for knownName in listKnownNames:
            knownImages = face_recognition.load_image_file(f"{knownPath}/{knownName}")
            k_pics.append(knownImages)

        u_pic = face_recognition.load_image_file(f"{unknownPath}/{listUnknownNames[0]}")

        return k_pics, u_pic
    
    # encode images
    def encode_images(self, known_images, unknown_images):
        encode_k = []
        for k in known_images:
            if face_recognition.face_encodings(k):
                    encode_k.append(face_recognition.face_encodings(k)[0])
        
        encode_u = []
        if face_recognition.face_encodings(unknown_images):
            encode_u = face_recognition.face_encodings(unknown_images)[0]
        
        return encode_k, encode_u
    
    # compare images, know the face distance, get the match name
    def compare(self, known_images_encoded, unknown_images_encoded):
        faceMatched = []
        likeliness = []
        names = []

        for known, name in zip(known_images_encoded, self.k):
            result = face_recognition.compare_faces([known], unknown_images_encoded)
            faceDist = face_recognition.face_distance([known], unknown_images_encoded)
            if result[0]:
                names.append(name)
                likeliness.append(faceDist)
                faceMatched.append(known)
        
        return faceMatched, likeliness, names
    
    # matched summary
    def matched_summary(self, likeliness, names):
        mostLikely = min(likeliness)
        location = likeliness.index(mostLikely)
        match_name = names[location]

        return mostLikely, match_name


def get_result(knownPath, unknownPath):
    
    known = os.listdir(knownPath)
    unknown = os.listdir(unknownPath)

    result = ""

    if known:
        if unknown:
            fr = FaceRecog(known, unknown)

            k_pics, u_pic = fr.get_images(knownPath, unknownPath)
            
            k_enc, u_enc = fr.encode_images(k_pics, u_pic)

            if k_enc:
                if any(u_enc):
                    faceMatch, likeliness, names = fr.compare(k_enc, u_enc)

                    if any(likeliness):
                        distance, name = fr.matched_summary(likeliness, names)
                        result = os.path.splitext(name)
                    else:
                        # 'No face matched'
                        result = "0"
                else:
                    # "No face found in unknown directory"
                    result = "1"
            else:
                # "Face not found in Known"
                result = "2"
        else:
            # "'Unknown' directory is empty."
            result = "3"
    else:
        # "'Known' directory is empty."
        result = "4"

    return result


# knownPath = os.path.join('templates', 'student_panel', 'img', 'known')
# unknownPath = os.path.join('templates', 'student_panel', 'img', 'unknown')

# result = get_result(knownPath, unknownPath)

# print(result)