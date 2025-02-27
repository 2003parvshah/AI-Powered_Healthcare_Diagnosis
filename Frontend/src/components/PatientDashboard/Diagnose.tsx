import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Send, ScanHeart, Paperclip, FileImage } from "lucide-react";
// import { Input } from "@/components/ui/input";

export const Diagnose = () => {
  const [message, setMessage] = useState("");
  const prompts = ["Running nose", "Fever with headache", "Red eyes"];

  return (
    <div className="flex h-[80vh] flex-col items-center justify-center p-6 text-center">
      {/* Icon */}
      <div className="rounded-full bg-gray-200 p-4">
        <ScanHeart size={32} className="text-gray-700" />
      </div>

      {/* Header Section */}
      <h1 className="mt-4 text-2xl font-bold">How do you feel Today?</h1>
      <p className="mt-2 text-gray-500">
        Tell me you symptoms to get AI generated Diagnoses
      </p>

      {/* Prompt Buttons */}
      <div className="mt-6 flex w-4xl flex-wrap items-center justify-center gap-3">
        {prompts.map((prompt, index) => (
          <Button key={index} variant="outline" className="px-4 py-2">
            {prompt}
          </Button>
        ))}
      </div>

      {/* Input Box */}
      <div className="mt-6 flex w-full max-w-2xl flex-col rounded-lg border border-gray-300 bg-white p-4 shadow-md">
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
        <div className="mt-12 flex items-center justify-between">
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
      </div>
    </div>
  );
};
