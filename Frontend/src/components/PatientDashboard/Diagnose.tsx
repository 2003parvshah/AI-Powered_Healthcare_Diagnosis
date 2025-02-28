import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Send, ScanHeart, Paperclip, FileImage } from "lucide-react";
// import { Input } from "@/components/ui/input";

import { motion } from "framer-motion";
export const Diagnose = () => {
  const [message, setMessage] = useState("");
  const prompts = ["Running nose", "Fever with headache", "Red eyes"];

  return (
    <motion.div
      initial={{ scale: 0.9, opacity: 0 }}
      animate={{ scale: 1, opacity: 1 }}
      transition={{ duration: 0.5 }}
      className="flex h-[80vh] flex-col items-center justify-center p-6 text-center"
    >
      {/* Icon */}
      <div className="bg-primary rounded-full p-4">
        <ScanHeart size={32} className="text-white" />
      </div>

      {/* Header Section */}
      <h1 className="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-3xl font-bold text-transparent">
        How do you feel Today?
      </h1>
      <motion.p
        initial={{ y: 10, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, delay: 0.2 }}
        className="mt-2 text-gray-500"
      >
        Tell me you symptoms to get AI generated Diagnoses
      </motion.p>

      {/* Prompt Buttons */}
      <motion.div
        initial={{ y: 20, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, delay: 0.4 }}
        className="mt-6 flex w-4xl flex-wrap items-center justify-center gap-3"
      >
        {prompts.map((prompt, index) => (
          <Button
            key={index}
            variant="outline"
            className="cursor-pointer rounded-full px-4 py-2 text-sm font-medium transition-all"
          >
            {prompt}
          </Button>
        ))}
      </motion.div>

      {/* Input Box */}
      <motion.div
        initial={{ y: 20, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.5, delay: 0.6 }}
        className="mt-6 flex w-full max-w-2xl flex-col rounded-lg border border-gray-300 bg-white shadow-md"
      >
        {/* <Input
          placeholder="What are the best open opportunities by company size?"
          className="flex items-center gap-2 border-0 text-gray-600 shadow-none focus:ring-0 focus-visible:ring-0"
        /> */}
        <textarea
          className="w-full resize-none border-none p-3 focus:ring-0 focus-visible:ring-0"
          placeholder="Ask about symptoms, treatments, or medical advice..."
          wrap="soft"
          rows={message ? Math.min(4, message.split("\n").length + 1) : 1}
          value={message}
          onChange={(e) => setMessage(e.target.value)}
        />

        {/* Dropdown & Send Button */}
        <div className="mt-12 flex items-center justify-between border-t border-t-blue-100 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 p-3">
          {/* <Button variant="outline" className="flex items-center gap-2">
            Salesforce <ChevronDown size={16} />
          </Button> */}
          <div className="flex items-center justify-center gap-2">
            <Button variant="ghost" size="icon">
              <Paperclip />
            </Button>
            <Button variant="ghost" size="icon">
              <FileImage />
            </Button>
          </div>

          <div className="flex items-center gap-2">
            <Button className="rounded-full p-2 text-white">
              Diagnose <Send size={18} />
            </Button>
          </div>
        </div>
      </motion.div>
    </motion.div>
  );
};
